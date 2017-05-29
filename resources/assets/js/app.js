
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example', require('./components/Example.vue'));

const app = new Vue({
    el: '#app'
});
 var id = document.getElementById('receiver_id').value
console.log(id);
//Echo.private(`order.${orderId}`)
/*
Echo.private('private-user.1', 'Alert', function(e){
    console.log('hello');
    toastr["info"]("you have a new article");
    console.log(e.user);
});
*/

Echo.channel('all-channel').listen('Alert', function(e) {
	if (id == e.user.id)
		toastr["info"]("you have a new article");
		console.log(e.user);
});


/*
Echo.channel('channel').listen(".Alert", function(e){
    	console.log('hello');
        //console.log(e.user);
    });
*/