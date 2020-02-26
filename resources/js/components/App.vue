<template>
    <div id="app-top-page">
        <div
            id="app-back-top"
            class="text-light text-center rounded-circle pt-3 bg-primary"
            style="display: none"
            @click="appToTop"
        >
            <fa :icon="['fas', 'angle-up']" size="lg" />
        </div>
        <nav
            v-if="!$auth.check() || $auth.check() && $auth.user().role === 0"
            class="navbar navbar-expand-md navbar-light shadow-sm bg-white"
        >
            <router-link class="navbar-brand" :to="$auth.check() ? '/home' : '/'">{{ appName }}</router-link>
            <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#app-nav"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/home">Home</router-link>
                    </li>
                    <li v-if="$auth.check()" class="nav-item">
                        <router-link class="nav-link" to="/home/post">Postinganku</router-link>
                    </li>
                </ul>
                <form autocomplete="off" class="d-inline w-50" @submit.prevent="appSearch">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <button type="submit" class="btn btn-outline-secondary">
                                <fa icon="search" />
                            </button>
                        </div>
                        <input
                            class="form-control"
                            type="text"
                            v-model="q"
                            placeholder="Cari postingan / artikel / rekomendasi perusahaan.."
                        />
                    </div>
                </form>
                <ul v-if="$auth.check()" class="navbar-nav ml-auto">
                    <div class="d-none">{{ appNotip() }}</div>
                    <li class="nav-item dropdown">
                        <a
                            id="profDropdown"
                            class="nav-link dropdown-toggle custom-force-img"
                            href="#"
                            data-toggle="dropdown"
                        >
                            <span
                                class="badge badge-primary"
                            >{{ appNotifCounter ? (appNotifCounter > 9 ? '9+' : appNotifCounter ) : '' }}</span>
                            {{ $auth.user().name }}
                            <img
                                class="img-fluid rounded-circle"
                                :src="$auth.user().avatar"
                            />
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <router-link
                                v-if="$auth.user().role !== 1"
                                class="dropdown-item"
                                to="/profile"
                            >
                                <fa :icon="['far', 'user']" />
                                {{ ' Profil' }}
                            </router-link>
                            <router-link
                                class="dropdown-item"
                                v-if="$auth.user().role !== 1"
                                to="/notification"
                            >
                                <fa :class="appNotifCounter ? 'text-primary' : ''" icon="bell" />
                                {{ ' Notifikasi' }}
                            </router-link>
                            <a class="dropdown-item" href="#" @click.prevent="$auth.logout()">
                                <fa icon="sign-out-alt" />
                                {{ ' Logout' }}
                            </a>
                        </div>
                    </li>
                </ul>
                <ul v-else class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/login">Login</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/register">Register</router-link>
                    </li>
                </ul>
            </div>
        </nav>
        <nav v-else class="navbar navbar-expand-md navbar-dark shadow-sm bg-primary">
            <router-link class="navbar-brand" to="/admin">{{ appName }}</router-link>
            <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#app-nav"
            >
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="app-nav">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <router-link class="nav-link" to="/admin">Dashboard</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/admin/users">Users</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/admin/posts">Posts</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/admin/reports">Reports</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/admin/messages">Messages</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" to="/admin/histories">History Deletes</router-link>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" @click.prevent="$auth.logout()">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <div
            class="container-fluid"
            :class="$router.currentRoute.name === 'welcome' || $router.currentRoute.name === 'not-found' ? 'px-0' : 'my-4'"
        >
            <transition
                name="fade"
                mode="out-in"
                @beforeLeave="before"
                @enter="enter"
                @afterEnter="after"
            >
                <router-view @appNotCount="appNotip"></router-view>
            </transition>
        </div>
        <footer class="container-fluid text-dark px-0 mx-0">
            <div class="py-2 bg-lighten">
                <p class="text-center">
                    &copy; {{ new Date().getFullYear() }}
                    <strong>
                        <router-link class="text-dark text-decoration-none" to="/">Forum RPL</router-link>
                    </strong>. All Right Reserved
                </p>
            </div>
        </footer>
    </div>
</template>

<script>
export default {
    data: () => ({
        appName: process.env.MIX_APP_NAME,
        q: "",
        appNotif: null,
        appNotifCounter: 0
    }),
    mounted() {
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) $("#app-back-top").fadeIn();
            else $("#app-back-top").fadeOut();
        });
    },
    methods: {
        before(el) {
            this.height = getComputedStyle(el).height;
        },
        enter(el) {
            const { height } = getComputedStyle(el);

            el.style.height = this.height;
            setTimeout(() => {
                el.style.height = height;
            });
        },
        after(el) {
            el.style.height = "auto";
        },
        appToTop() {
            $("body,html").animate(
                {
                    scrollTop: 0
                },
                250
            );
        },
        appSearch() {
            if (this.q != "") {
                if (this.$router.currentRoute.name === "search")
                    this.$router.replace({ query: { q: this.q } });
                else
                    this.$router
                        .push({
                            path: "/search",
                            query: Object.assign(this.$route.query, {
                                q: this.q
                            })
                        })
                        .catch(() => {});
            }
        },
        appNotip() {
            axios
                .post(`user/notif/${this.$auth.user().id}`)
                .then(resp => (this.appNotifCounter = resp.data))
                .catch(err => console.error(err.response));
        }
    }
};
</script>
