# Framework used
- [Laravel](https://laravel.com/docs/master/installation)
- [Vue.js](https://vuejs.org/v2/guide/installation.html)

### Module
- Back-end
	- [Socialite](https://laravel.com/docs/master/socialite)
	- [Laravel UI](https://laravel.com/docs/master/frontend)
	- [JWT Auth](https://github.com/tymondesigns/jwt-auth)
- Front-end
	- [Font Awesome](https://www.npmjs.com/package/font-awesome)
	- [Apex Charts](https://apexcharts.com/docs/vue-charts)
	- [ES6 Promise](https://www.npmjs.com/package/es6-promise)
	- [Laravel Vue Pagination](https://www.npmjs.com/package/laravel-vue-pagination)
	- [Markdown It Vue](https://www.npmjs.com/package/markdown-it-vue)
	- [Vue Axios](https://www.npmjs.com/package/vue-axios)
	- [Vue Infinite Loading](https://www.npmjs.com/package/vue-infinite-loading)
	- [Vue Recaptcha](https://www.npmjs.com/package/vue-recaptcha)
	- [Vue Session](https://www.npmjs.com/package/vue-session)
	- [Vue SimpleMDE](https://www.npmjs.com/package/vue-simplemde)

### Third party
- [Facebook Developer](https://developers.facebook.com)
- [Google Developer Console](https://console.developers.google.com)

# Installation

### Requirements
- [Composer](https://getcomposer.org/download) >= 1.7.2
- [Git](https://git-scm.com/downloads)
- ~~[Node.js](https://nodejs.org/en/download/current) >= 12.4.0~~
- [Visual Code](https://code.visualstudio.com/download) *optional*
- [XAMPP](https://www.apachefriends.org/download.html) >= 3.2.2

### Install
1. Clone this project `git clone https://github.com/AnOrdinaryPeople/ujikom.git`
1. Duplicate **.env.example** `cp .env.example .env`
1. Set up the **.env**
	1. Database
	1. Mail (for mailgun)
	1. Recaptcha
	1. Google and Facebook API
1. Install composer `composer i`
1. ~~Install Node.js `npm i`~~
1. Create JWT Secret `php artisan jwt:secret`
1. Migrate table and make seeder `php artisan migrate --seed`
1. Generate the key `php artisan key:generate`
1. Run the server `php artisan serve`
