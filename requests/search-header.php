<?php 
$deliver['status'] = 400;
if (!empty($_POST['keyword'])) {
	$html = '';
	$keyword = Specific::Filter($_POST['keyword']);
	$search_result = $dba->query('SELECT tags FROM videos v WHERE (tags LIKE "%'.$keyword.'%") AND ((SELECT live FROM broadcasts WHERE video_id = v.video_id) = 1 OR (SELECT live FROM broadcasts WHERE video_id = v.video_id) = 3 OR broadcast = 0) AND privacy = 0 AND deleted = 0 LIMIT 10')->fetchAll();
	if (!empty($search_result)) {
		$all_t = array();
		foreach ($search_result as $search) {
			$arr_allt = explode(',', $search['tags']);
			$match_allt = array_filter($arr_allt, function($var) use ($keyword) { 
				return stristr($var, $keyword); 
			});
			foreach ($match_allt as $value) {
				$all_t[] = strtolower($value);
			}		
		}
		$tags_diff = array_unique($all_t);
		$suggestionCk = json_decode($_COOKIE['searchSuggestionHistory'], true);
		$inSuggetions = '';
		foreach ($tags_diff as $val) {
			if($suggestionCk['id'] == $TEMP['#user']['id'] && !empty($suggestionCk)){
				$inSuggetions = '';
			    foreach ($suggestionCk['suggestions'] as $value) {
			        if(in_array($val, $value) && in_array($value['suggestion'], $tags_diff)){
			            $inSuggetions = '<svg class="vh-icon color-invert" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="currentColor" d="M 21 12 C 21 13.219 20.762 14.383 20.285 15.492 C 19.809 16.602 19.168 17.559 18.363 18.363 C 17.559 19.168 16.602 19.809 15.492 20.285 C 14.383 20.762 13.219 21 12 21 C 10.656 21 9.379 20.717 8.168 20.15 C 6.957 19.584 5.926 18.785 5.074 17.754 C 5.02 17.676 4.994 17.588 4.998 17.49 C 5.002 17.393 5.035 17.312 5.098 17.25 L 6.703 15.633 C 6.781 15.563 6.879 15.527 6.996 15.527 C 7.121 15.543 7.211 15.59 7.266 15.668 C 7.836 16.41 8.535 16.984 9.363 17.391 C 10.191 17.797 11.07 18 12 18 C 12.813 18 13.588 17.842 14.326 17.525 C 15.064 17.209 15.703 16.781 16.242 16.242 C 16.781 15.703 17.209 15.064 17.525 14.326 C 17.842 13.588 18 12.812 18 12 C 18 11.188 17.842 10.412 17.525 9.674 C 17.209 8.936 16.781 8.297 16.242 7.758 C 15.703 7.219 15.064 6.791 14.326 6.475 C 13.588 6.158 12.813 6 12 6 C 11.234 6 10.5 6.139 9.797 6.416 C 9.094 6.693 8.469 7.09 7.922 7.605 L 9.527 9.223 C 9.77 9.457 9.824 9.727 9.691 10.031 C 9.559 10.344 9.328 10.5 9 10.5 L 3.75 10.5 C 3.547 10.5 3.371 10.426 3.223 10.277 C 3.074 10.129 3 9.953 3 9.75 L 3 4.5 C 3 4.172 3.156 3.941 3.469 3.809 C 3.773 3.676 4.043 3.73 4.277 3.973 L 5.801 5.484 C 6.637 4.695 7.592 4.084 8.666 3.65 C 9.74 3.217 10.852 3 12 3 C 13.219 3 14.383 3.238 15.492 3.715 C 16.602 4.191 17.559 4.832 18.363 5.637 C 19.168 6.441 19.809 7.398 20.285 8.508 C 20.762 9.617 21 10.781 21 12 Z M 13.5 8.625 L 13.5 13.875 C 13.5 13.984 13.465 14.074 13.395 14.145 C 13.324 14.215 13.234 14.25 13.125 14.25 L 9.375 14.25 C 9.266 14.25 9.176 14.215 9.105 14.145 C 9.035 14.074 9 13.984 9 13.875 L 9 13.125 C 9 13.016 9.035 12.926 9.105 12.855 C 9.176 12.785 9.266 12.75 9.375 12.75 L 12 12.75 L 12 8.625 C 12 8.516 12.035 8.426 12.105 8.355 C 12.176 8.285 12.266 8.25 12.375 8.25 L 13.125 8.25 C 13.234 8.25 13.324 8.285 13.395 8.355 C 13.465 8.426 13.5 8.516 13.5 8.625 Z"/></svg>';
			            break;
				    }
				}
			}
			$html .= "<a class='display-flex padding-10 action-list-hover border-top border-tertiary' href='".Specific::Url("search?keyword=$val")."' target='_self'><div class='margin-right-auto color-secondary'>".$val.'</div>'.$inSuggetions.'</a>';
		}
		$deliver = array(
			'status' => 200,
			'html' => $html
		);
	}
}
?>