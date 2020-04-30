<template>
    <div class="jumbroton jumbroton-fluid mt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-9 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <ul class="nav nav-tabs card-header-tabs text-center row">
                                <li class="nav-item col-6">
                                    <router-link class="nav-link" to="/login">Login</router-link>
                                </li>
                                <li class="nav-item col-6">
                                    <router-link class="nav-link" to="/register">Register</router-link>
                                </li>
                            </ul>
                        </div>
                        <div class="card-body">
                            <div
                                v-if="has_error"
                                class="alert alert-danger"
                            >Email atau password salah</div>
                            <div
                                v-if="this.notif"
                                class="alert alert-info"
                            >Cek email kamu untuk verifikasi</div>
                            <div
                                v-else-if="this.notiff"
                                class="alert alert-info"
                            >Password telah diubah</div>
                            <form autocomplete="off" @submit.prevent="login()" method="post">
                                <div class="form-group">
                                    <label for="log-email">
                                        <fa icon="envelope" />
                                        {{ ' Email' }}
                                    </label>
                                    <input
                                        id="log-email"
                                        class="form-control"
                                        type="email"
                                        v-model="email"
                                        required
                                        autofocus
                                    />
                                </div>
                                <div class="form-group">
                                    <label for="log-pass">
                                        <fa icon="key" />
                                        {{ ' Password' }}
                                    </label>
                                    <input
                                        id="log-pass"
                                        class="form-control"
                                        type="password"
                                        v-model="pass"
                                        required
                                    />
                                </div>
                                <div class="custom-control custom-checkbox mb-2">
                                    <input
                                        id="check-first"
                                        class="custom-control-input"
                                        type="checkbox"
                                        v-model="remember"
                                        value="1"
                                    />
                                    <label
                                        class="custom-control-label"
                                        for="check-first"
                                    >Remember me</label>
                                </div>
                                <button class="btn btn-primary" :disabled="clicked === true">
                                    <fa icon="sign-in-alt" />
                                    {{ ' Masuk' }}
                                </button>
                                <fa
                                    v-if="clicked"
                                    icon="spinner"
                                    spin
                                    size="lg"
                                    class="text-primary"
                                />
                                <router-link class="btn" to="/forgot">Lupa password?</router-link>
                            </form>
                            <socialite />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        email: "",
        pass: "",
        remember: "",
        has_error: false,
        clicked: false,
        notif: false,
        notiff: false
    }),
    mounted() {
        const q = this.$route.query.token;

        if (this.$session.has("notif")) {
            this.notif = true;
            this.$session.remove("notif");
        } else if (this.$session.has("notiff")) {
            this.notiff = true;
            this.$session.remove("notiff");
        }

        if (q) {
            const data = JSON.parse(atob(q));
            this.login(data.email, data.pass);
        }
    },
    methods: {
        login(e = "", p = "") {
            this.clicked = true;
            this.has_error = false;

            this.$auth.login({
                data: {
                    email: e !== "" ? e : this.email,
                    password: p !== "" ? p : this.pass
                },
                success: () => {
                    this.$router.push({ name: "home" });
                    this.clicked = false;
                },
                error: err => {
                    this.has_error = true;
                    this.clicked = false;
                },
                rememberMe: this.remember == 1 ? true : false,
                fetchUser: true
            });
        }
    }
};
</script>
