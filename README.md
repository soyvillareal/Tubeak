# Tubeak
During the sixth semester of my university career, I focused on developing a project that was complex enough to feel challenged, I was looking to apply the knowledge that I had already acquired in the development of other less complicated projects. I decided that this would be a long-term project, at least for what was left of my college career.

This was the first project I did with a final product in mind, something that could be used by other people, and although I made many mistakes (too many), today I feel satisfied with what this project has meant to me.


<div align="center">
  <img src="https://soyvillareal.com/docs/tubeak/images/banner.jpg" alt="Banner">
</div>

# Known issues
- Compromised security: due to the low development standards implemented in this project, the door was opened to security vulnerabilities related to **Cross Site Scripting (XSS)**, you can see more information about this [here](https://owasp.org/www-community/attacks/xss/).

## Donate
<span class="badge-paypal"><a href="https://www.paypal.me/SoyVillareal" title="Donate to this project using Paypal" target="_blank"><img src="https://img.shields.io/badge/paypal-donate-yellow.svg?style=for-the-badge&logo=paypal" alt="PayPal donate button" /></a></span>

Due to the high consumption of resources that this platform represents, I have not had the resources to prepare a **demo** at this time. However, if you want to help me with this you can do so by giving me a little bit of your capitalism ;)


## Table of contents:
- [Important Information](#important-information)
- [Server Requirements](#server-requirements)
- [Dependencies installed](#make-sure-you-have-the-following-dependencies-installed)
- [For install](#for-install)
- [Features](#features)
  - [Administrator](#administrator)
  - [Moderator](#moderator) 
  - [User](#user) 
- [Documentation](#documentation)
- [Contributing](#contributing)
- [License](#license)


## Important Information

> This project is not made to be put into production, it was its purpose at the beginning. However, it has security issues as [mentioned above](#known-issues), even so, if you have programming knowledge you can fix them without much difficulty.

### Server Requirements
- PHP 5.5+
- MySQL 5.0+
- Apache 2.x+ or Nginx (NGINX is required for live streaming)


### Make sure you have the following dependencies installed
- MySQLi
- cURL
- GD Library
- MBstring
- Mail
- OpenSSL

## For install:

1. Clone the repository
```bash
git clone https://github.com/soyvillareal/Tubeak.git
```
2. Once you clone the repository just go to the URL that points to where it is hosted and follow the easy to use installer.

3. Enjoyment! Yes that easy :D

## Features
These are some of the features that this project contains. However, it should be noted that it is much more complex than what can be shown here.

### Administrator

- Dashboard: You can see the statistics of your platform; videos, views, users, subscriptions, comments, likes, dislikes, playlists.
- Settings: You can customize your platform, we offer extensive configurations (at a general level, website and mail) to allow you to easily manage your entire site.
- Languages: You can add new languages or edit existing ones, you can even add new words to all languages.
- Pages: You can edit your pages; terms of use, terms of service and about us.
- Users: From here you can manage your users, take actions on them, view their information, view verification requests and even apply locks (For example: user, email or ip).
- Videos: Here you can manage all videos and finished live streams. Also can; filter, search and take action on them.
- Categories: You can add new categories, view existing ones, and edit or delete them.
- Comments: Here you can see all the comments made on your platform, you can search for them and take action on them.
- Reports: Here you can see all the reported content, depending on its nature some options will be provided to apply actions.
- Advertising: Here you can place your ads (HTML is supported), you decide which sections to enable and where to display the ads.

### Moderator

- Users: Here you can search and see all users, information and take actions on them (A moderator cannot take action on other moderators or an administrator). You can also apply locks (For example: user, email or ip) and view verification requests.
- Videos: Here you can manage all videos and finished live streams. Also can; filter, search and take action on them.
- Comments: Here you can see all the comments made on your platform, you can search for them and take action on them.
- Reports: Here you can see all the reported content, depending on its nature some options will be provided to apply actions.

### User

- Live streaming: Give your users the possibility to live streams and interact with their public through live chat, all with the best quality.
- Live chat (Live streaming): Interact with a creator's community or with your own community using the live chat located on the side of the broadcast and in the broadcast panel.
- Upload video: Your users can upload videos up to 4k, Tubeak Pro handles HD and 4k videos with high performance.
- Report: Take care of your community and the entire community in general. To prevent some users from abusing their stay on the platform, you can report them if you think they have published, transmitted, commented or carried out any action that goes against the guidelines of the platform, you can even report specific content.
- History: You can see all your video history and keep abreast of everything you have seen on the platform.
- Subscription: You can subscribe to your favorite channels and be aware of all their news through notifications.
- Likes and Dislikes: Express your opinion with these options, you can react to a video or comment, giving them like or dislike.
- Comment: You can comment on videos and express your opinion about the content you have seen.
- Playlists: Create your own playlists or use your existing one to save your videos or live broadcasts for later viewing.
- Notifications: You may receive notifications about; videos, live streams, comments, responses, and requests.
- Settings: You can configure your account, the settings are intended to personalize your account and keep it safe, you have options; general, watermark, blocked users, change password, delete account and security.
- Change Banner and Photo: From your channel you can change your banner and photo, to personalize it and consolidate your brand in front of your community.
- Video studio: Here you can see the statistics of your channel; comments, views, likes, dislikes and subscribers. You can also see all your videos and their statistics, the comments made on your videos and take action on them.
- Language: You can choose the language that best suits your abilities within the 8 languages available in this script (English, Arabic, Dutch, French, German, Russian, Spanish or Turkish)
- Dark and light mode: To provide the best experience we give you the option to choose how you want to navigate the platform, in light mode or dark mode.

## Preview
In view of the absence of a live demo, I choose to leave here images of some views of the platform, note:

<div align="center">
  <img src="https://soyvillareal.com/docs/tubeak/images/devices.png">
  <p>It is responsive and adaptable to any device</p>
</div>

### User

#### Home:

![Home](https://soyvillareal.com/docs/tubeak/images/screenshots/user/01_preview.png)

#### Live broadcast:

![Live broadcast](https://soyvillareal.com/docs/tubeak/images/screenshots/user/02_preview.png)

#### Upload video:

![Upload video 1](https://soyvillareal.com/docs/tubeak/images/screenshots/user/03_preview.png)

![Upload video 2](https://soyvillareal.com/docs/tubeak/images/screenshots/user/04_preview.png)

#### Uploading video:

![Uploading video](https://soyvillareal.com/docs/tubeak/images/screenshots/user/05_preview.png)

#### Watch video on desktop:

![Watch video on desktop](https://soyvillareal.com/docs/tubeak/images/screenshots/user/06_preview.png)

#### Watch video on mobile:

<div align="center">
  <img src="https://soyvillareal.com/docs/tubeak/images/screenshots/user/07_preview.png" alt="Watch video on mobile">
  <p>It is responsive and adaptable to any device</p>
</div>

#### Watch playlist:

![Watch playlist](https://soyvillareal.com/docs/tubeak/images/screenshots/user/08_preview.png)

#### Comments:

![Comments](https://soyvillareal.com/docs/tubeak/images/screenshots/user/09_preview.png)

#### History:

![History](https://soyvillareal.com/docs/tubeak/images/screenshots/user/10_preview.png)

#### Search video:

![Search video](https://soyvillareal.com/docs/tubeak/images/screenshots/user/11_preview.png)

#### User statistics:

![User statistics](https://soyvillareal.com/docs/tubeak/images/screenshots/user/12_preview.png)

#### User video:

![User video](https://soyvillareal.com/docs/tubeak/images/screenshots/user/13_preview.png)

#### Watermark settings:

![Watermark Settings](https://soyvillareal.com/docs/tubeak/images/screenshots/user/14_preview.png)

#### Channel:

![Channel](https://soyvillareal.com/docs/tubeak/images/screenshots/user/15_preview.png)

#### Playlists:

![Playlists](https://soyvillareal.com/docs/tubeak/images/screenshots/user/16_preview.png)

#### Liked videos:

![Liked videos](https://soyvillareal.com/docs/tubeak/images/screenshots/user/17_preview.png)

#### More info:

![More Info](https://soyvillareal.com/docs/tubeak/images/screenshots/user/18_preview.png)


### Administrators and moderators

#### Admin statistics

![Admin statistics](https://soyvillareal.com/docs/tubeak/images/screenshots/admin-moderator/19_preview.png)

#### All languages

![All languages](https://soyvillareal.com/docs/tubeak/images/screenshots/admin-moderator/20_preview.png)

#### Manage and edit videos

![Manage and edit videos](https://soyvillareal.com/docs/tubeak/images/screenshots/admin-moderator/21_preview.png)

#### Website Settings

![Website Settings](https://soyvillareal.com/docs/tubeak/images/screenshots/admin-moderator/22_preview.png)

# Documentation
This project is meticulously documented, if you have any problem with its operation or installation, you can visit the documentation [here](https://soyvillareal.com/docs/tubeak/documentation/index.html).

# Contributing
This is a project that today I have finished. However, if you wish to collaborate, you are welcome. Send through a Pull Request and i will review it ASAP.

# License
Tubeak is distributed under the terms of the [MIT License](LICENSE.md).
