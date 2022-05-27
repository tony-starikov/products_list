<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- font-awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <title>Products App</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
</head>
<body>
<div id="app">
    <router-view></router-view>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

<script src="https://unpkg.com/vue@3"></script>
<script src="https://unpkg.com/vuex@4"></script>
<script src="https://unpkg.com/vue-router@4"></script>

<script>
    const Cart = {
        data() {
            return {
                cart: [],
            }
        },
        created() {
            this.getData();
        },
        mounted() {
            this.checkCart();
        },
        methods:{
            async getData() {
                this.cart = this.$store.state.cart;
            },
            cartPlus(e) {
                const id = e.target.id;
                this.$store.state.cart.map((product) => {
                    if (product.id === id) {
                        if (product.quantity == 0) {
                            this.showMessage('Not Enough Products To Add', 'alert alert-danger mt-2 text-center');
                        } else {
                            product.quantity--;
                            product.inCart++;
                            this.$store.commit('incrementCart');
                        }
                    }
                });
                this.cart = this.$store.state.cart;
            },
            cartMinus(e) {
                const id = e.target.id;
                this.$store.state.cart.map((product) => {
                    if (product.id === id) {
                        if (product.inCart == 1) {
                            this.removeFromCart(e);
                        } else {
                            product.quantity++;
                            product.inCart--;
                        }
                        this.$store.commit('decrementCart');
                    }
                });
                this.cart = this.$store.state.cart;
            },
            removeFromCart(e){
                const id = e.target.id;
                const product =  this.cart.find((item) => {
                    return item.id === id;
                });
                this.$store.commit('removeProductFromCart', product);
                this.cart = this.cart.filter(function(value){
                    return value.id !== id;
                });
            },
            showMessage(text, classes) {
                const message = document.getElementById('message');
                message.className = classes;
                const p =  document.createElement('h5');
                p.innerText = text;
                message.appendChild(p);

                setTimeout(() => {
                    message.className = '';
                    message.innerHTML = '';
                }, 3000);
            },
            checkCart() {
                if (this.$store.state.wishlist.length == 0) {
                    this.showMessage('Add Products From Products Page', 'alert alert-danger mt-2 text-center');
                }
            },
        },
        template: '' +
            '<header class="d-flex justify-content-center py-3 bg-dark text-white">\n' +
            '        <ul class="nav nav-pills">\n' +
            '            <li class="nav-item"><router-link to="/" class="nav-link px-2 text-white">PRODUCTS</router-link></li>\n' +
            '            <li class="nav-item">\n' +
            '                <router-link to="/cart" class="nav-link px-2 text-white">\n' +
            '                    CART\n' +
            '                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>\n' +
            '                    <span class="ms-2 badge bg-primary">{{ this.$store.state.cartCounter }}</span>\n' +
            '                </router-link>\n' +
            '            </li>\n' +
            '            <li class="nav-item">\n' +
            '                <router-link to="/wishlist" class="nav-link px-2 text-white">\n' +
            '                    WISHLIST\n' +
            '                    <i class="fa fa-heart" aria-hidden="true"></i>\n' +
            '                    <span class="ms-2 badge bg-primary">{{ this.$store.state.wishlistCounter }}</span>\n' +
            '                </router-link>\n' +
            '            </li>\n' +
            '        </ul>\n' +
            '</header>' +

            '<div class="container">' +
            '        <div class="row">' +
            '            <div id="message"></div>' +
            '            <div v-for="product in cart" :key="product.id" class="col-lg-4">' +
            '               <div class="card m-2">\n' +
            '                   <img src="images/img.png" class="card-img-top img-fluid" alt="img">\n' +
            '                   <div class="card-body">\n' +
            '                       <h5 class="card-title">{{ product.name }}</h5>\n' +
            '                       <h6 class="card-title">price: {{ product.price }}$</h6>\n' +
            '                       <h6 class="card-title">available: {{ product.quantity }}</h6>\n' +
            '                       <button v-bind:id="product.id" @click.prevent="cartPlus" class="btn btn-primary ms-2 mt-2">ADD</button>\n' +
            '                       <button class="btn btn-primary ms-2 mt-2">IN CART: {{ product.inCart }}</button>\n' +
            '                       <button v-bind:id="product.id" @click.prevent="cartMinus" class="btn btn-primary ms-2 mt-2">DELETE</button>\n' +
            '                   </div>\n' +
            '               </div>' +
            '            </div>' +
            '       </div>' +
            '</div>',
    };
    const Wishlist = {
        data() {
            return {
                products: [],
                wishlist: [],
            }
        },
        created() {
            this.getData();
        },
        mounted() {
            this.checkCart();
        },
        methods:{
            async getData() {
                const response = await fetch('http://localhost/products');
                this.products = await response.json();
                this.wishlist = this.$store.state.wishlist;
            },
            removeFromWishlist(e){
                const id = e.target.id;
                const product =  this.products.find((item) => {
                    return item.id === id;
                });
                this.$store.commit('removeProductFromWishlist', product);
                this.wishlist = this.wishlist.filter(function(value){
                    return value.id !== id;
                });
            },
            showMessage(text, classes) {
                const message = document.getElementById('message');
                message.className = classes;
                const p =  document.createElement('h5');
                p.innerText = text;
                message.appendChild(p);

                setTimeout(() => {
                    message.className = '';
                    message.innerHTML = '';
                }, 3000);
            },
            checkCart() {
                if (this.$store.state.wishlist.length == 0) {
                    this.showMessage('Add Products From Products Page', 'alert alert-danger mt-2 text-center');
                }
            },
        },
        template: '' +
            '<header class="d-flex justify-content-center py-3 bg-dark text-white">\n' +
            '        <ul class="nav nav-pills">\n' +
            '            <li class="nav-item"><router-link to="/" class="nav-link px-2 text-white">PRODUCTS</router-link></li>\n' +
            '            <li class="nav-item">\n' +
            '                <router-link to="/cart" class="nav-link px-2 text-white">\n' +
            '                    CART\n' +
            '                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>\n' +
            '                    <span class="ms-2 badge bg-primary">{{ this.$store.state.cartCounter }}</span>\n' +
            '                </router-link>\n' +
            '            </li>\n' +
            '            <li class="nav-item">\n' +
            '                <router-link to="/wishlist" class="nav-link px-2 text-white">\n' +
            '                    WISHLIST\n' +
            '                    <i class="fa fa-heart" aria-hidden="true"></i>\n' +
            '                    <span class="ms-2 badge bg-primary">{{ this.$store.state.wishlistCounter }}</span>\n' +
            '                </router-link>\n' +
            '            </li>\n' +
            '        </ul>\n' +
            '</header>' +

            '<div class="container">' +
            '        <div class="row">' +
            '            <div id="message"></div>' +
            '            <div v-for="product in wishlist" :key="product.id" class="col-lg-4">' +
            '               <div class="card m-2">\n' +
            '                   <img src="images/img.png" class="card-img-top img-fluid" alt="img">\n' +
            '                   <div class="card-body">\n' +
            '                       <h5 class="card-title">{{ product.name }}</h5>\n' +
            '                       <h6 class="card-title">price: {{ product.price }}$</h6>\n' +
            '                       <h6 class="card-title">quantity: {{ product.quantity }}</h6>\n' +
            '                       <a href="#" v-bind:id="product.id" @click.prevent="removeFromWishlist" class="btn btn-primary ms-2 mt-2">Remove From Wishlist <i class="fa fa-heart" aria-hidden="true"></i></a>\n' +
            '                   </div>\n' +
            '               </div>' +
            '            </div>' +
            '       </div>' +
            '</div>',
    };
    const Products = {
        data() {
            return {
                products: [],
            }
        },
        created() {
            this.getData();
        },
        methods:{
            async getData() {
                const response = await fetch('http://localhost/products');
                this.products = await response.json();
                this.products.map((product) => {
                    product.inCart = 0;
                    product.quantity = parseInt(product.quantity);
                });
            },
            addToWishlist(e){
                const id = e.target.id;
                const product =  this.products.find((item) => {
                    return item.id === id;
                });
                if (!this.$store.state.wishlist.includes(product)) {
                    this.$store.commit('addProductToWishlist', product);
                    this.showMessage('Product Added To Wishlist', 'alert alert-danger mt-2 text-center');
                } else {
                    this.showMessage('Already In Wishlist', 'alert alert-danger mt-2 text-center');
                }
            },
            addToCart(e){
                const id = e.target.id;
                const product =  this.products.find((item) => {
                    return item.id === id;
                });
                if (!this.$store.state.cart.find((item) => {
                    return item.id === id;
                })) {
                    this.$store.commit('addProductToCart', product);
                    this.showMessage('Product Added To Cart', 'alert alert-danger mt-2 text-center');
                } else {
                    this.showMessage('Already In Cart', 'alert alert-danger mt-2 text-center');
                }
            },
            showMessage(text, classes) {
                const message = document.getElementById('message');
                message.className = classes;
                const p =  document.createElement('h5');
                p.innerText = text;
                message.appendChild(p);

                setTimeout(() => {
                    message.className = '';
                    message.innerHTML = '';
                }, 3000);
            },
        },
        template: '' +
            '<header class="d-flex justify-content-center py-3 bg-dark text-white">\n' +
            '        <ul class="nav nav-pills">\n' +
            '            <li class="nav-item"><router-link to="/" class="nav-link px-2 text-white">PRODUCTS</router-link></li>\n' +
            '            <li class="nav-item">\n' +
            '                <router-link to="/cart" class="nav-link px-2 text-white">\n' +
            '                    CART\n' +
            '                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>\n' +
            '                    <span class="ms-2 badge bg-primary">{{ this.$store.state.cartCounter }}</span>\n' +
            '                </router-link>\n' +
            '            </li>\n' +
            '            <li class="nav-item">\n' +
            '                <router-link to="/wishlist" class="nav-link px-2 text-white">\n' +
            '                    WISHLIST\n' +
            '                    <i class="fa fa-heart" aria-hidden="true"></i>\n' +
            '                    <span id="wishlistCounter" class="ms-2 badge bg-primary">{{ this.$store.state.wishlistCounter }}</span>\n' +
            '                </router-link>\n' +
            '            </li>\n' +
            '        </ul>\n' +
            '</header>' +

            '<div class="container">' +
            '        <div class="row">' +
            '            <div id="message"></div>' +
            '            <div v-for="product in products" :key="product.id" class="col-lg-4">' +
            '               <div class="card m-2">\n' +
            '                   <img src="images/img.png" class="card-img-top img-fluid" alt="img">\n' +
            '                   <div class="card-body">\n' +
            '                       <h5 class="card-title">{{ product.name }}</h5>\n' +
            '                       <h6 class="card-title">price: {{ product.price }}$</h6>\n' +
            '                       <h6 class="card-title">quantity: {{ product.quantity }}</h6>\n' +
            '                       <a href="#" v-bind:id="product.id" @click.prevent="addToWishlist" class="btn btn-primary ms-2 mt-2">Add To Wishlist <i class="fa fa-heart" aria-hidden="true"></i></a>\n' +
            '                       <a href="#" v-bind:id="product.id" @click.prevent="addToCart" class="btn btn-primary ms-2 mt-2">Add To Cart <i class="fa fa-shopping-cart" aria-hidden="true"></i></a>\n' +
            '                   </div>\n' +
            '               </div>' +
            '            </div>' +
            '       </div>' +
            '</div>',
    };

    const routes = [
    { path: '/', component: Products },
    { path: '/cart', component: Cart },
    { path: '/wishlist', component: Wishlist },
    ];

    const router = VueRouter.createRouter({
        history: VueRouter.createWebHistory(),
        base: '/',
        routes,
    });

    const store = Vuex.createStore({
        state () {
            return {
                cartCounter: 0,
                cart: [],
                wishlistCounter: 0,
                wishlist: [],
            }
        },
        mutations: {
            addProductToWishlist (state, product) {
                state.wishlist.push(product);
                state.wishlistCounter++;
            },
            removeProductFromWishlist (state, product) {
                state.wishlist = state.wishlist.filter(function(value){
                    return value.id !== product.id;
                });
                state.wishlistCounter--;
            },
            removeProductFromCart (state, product) {
                state.cart = state.cart.filter(function(value){
                    return value.id !== product.id;
                });
            },
            addProductToCart (state, product) {
                product.quantity--;
                product.inCart++;
                state.cart.push(product);
                state.cartCounter++;
            },
            incrementCart (state) {
                state.cartCounter++;
            },
            decrementCart (state) {
                state.cartCounter--;
            },
        }
    });

    const app = Vue.createApp({

    });

    app.use(router);
    app.use(store);

    app.mount('#app');
</script>

</body>
</html>
