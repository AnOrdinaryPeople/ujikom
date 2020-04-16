<template>
    <div class="row">
        <div class="col-2 bg-white sticky-top overflow-auto custom-h-100-vh shadow-sm">
            <nav class="nav flex-column nav-pills pt-3">
                <router-link
                    v-if="$auth.check()"
                    class="nav-link"
                    to="/developer/dashboard"
                >Dashboard</router-link>
                <router-link class="nav-link" to="/developer/starter">Getting Started</router-link>
                <a
                    href="#"
                    class="nav-link"
                    data-toggle="collapse"
                    data-target="#app-sidebar-doc"
                    @click.prevent="icon()"
                >
                    <fa :icon="clicked ? 'angle-up' : 'angle-down'" />
                    {{ ' Dokumen' }}
                </a>
                <div id="app-sidebar-doc" class="collapse ml-4">
                    <router-link class="nav-link" to="/developer/docs/user">User</router-link>
                    <router-link class="nav-link" to="/developer/docs/post">Postingan</router-link>
                    <router-link class="nav-link" to="/developer/docs/comment">Komentar</router-link>
                </div>
                <a
                    href="#"
                    class="nav-link"
                    data-toggle="collapse"
                    data-target="#app-sidebar-tutorial"
                    @click.prevent="icon(1)"
                >
                    <fa :icon="clickedd ? 'angle-up' : 'angle-down'" />
                    {{ ' Tutorial Singkat' }}
                </a>
                <div id="app-sidebar-tutorial" class="collapse ml-4">
                    <router-link class="nav-link" to="/developer/tutorial/php">PHP</router-link>
                    <router-link class="nav-link" to="/developer/tutorial/jquery">jQuery</router-link>
                    <router-link class="nav-link" to="/developer/tutorial/nodejs">Node.js</router-link>
                </div>
            </nav>
        </div>
        <div class="col-10 pt-3">
            <transition
                name="fade"
                mode="out-in"
                @beforeLeave="before"
                @enter="enter"
                @afterEnter="after"
            >
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<style scoped>
.collapse > .nav-link {
    color: #3490dc;
    text-decoration: none;
    background-color: transparent;
}
.collapse > .nav-link.active {
    color: #fff;
    background-color: #3490dc;
}
</style>

<script>
export default {
    data: () => ({
        clicked: false,
        clickedd: false
    }),
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
        icon(i = 0) {
            i
                ? (this.clickedd = !this.clickedd)
                : (this.clicked = !this.clicked);
        }
    }
};
</script>
