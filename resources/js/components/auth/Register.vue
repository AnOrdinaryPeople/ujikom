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
                            <form autocomplete="off" @submit.prevent="register" method="post">
                                <div class="form-group">
                                    <label for="log-name">
                                        <fa icon="user" />
                                        {{ ' Username' }}
                                    </label>
                                    <input
                                        id="log-name"
                                        class="form-control"
                                        :class="errors.name ? 'is-invalid' : ''"
                                        type="text"
                                        v-model="name"
                                        required
                                        autofocus
                                    />
                                    <small
                                        v-for="(i, key) in errors.name"
                                        :key="key"
                                        class="text-danger"
                                    >
                                        {{ i }}
                                        <br />
                                    </small>
                                </div>
                                <div class="form-group">
                                    <label for="log-email">
                                        <fa icon="envelope" />
                                        {{ ' Email' }}
                                    </label>
                                    <input
                                        id="log-email"
                                        class="form-control"
                                        :class="errors.email ? 'is-invalid' : ''"
                                        type="email"
                                        v-model="email"
                                        required
                                    />
                                    <small
                                        v-for="(i, key) in errors.email"
                                        :key="key"
                                        class="text-danger"
                                    >
                                        {{ i }}
                                        <br />
                                    </small>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="log-pass">
                                                <fa icon="key" />
                                                {{ ' Password' }}
                                            </label>
                                            <input
                                                id="log-pass"
                                                class="form-control"
                                                :class="errors.password ? 'is-invalid' : ''"
                                                type="password"
                                                v-model="pass"
                                                placeholder="min. password length 8"
                                                title="min. password length 8"
                                                required
                                            />
                                            <small
                                                v-for="(i, key) in errors.password"
                                                :key="key"
                                                class="text-danger"
                                            >
                                                {{ i }}
                                                <br />
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-sm-12">
                                        <div class="form-group">
                                            <label for="log-pass-con">Konfirmasi Password</label>
                                            <input
                                                id="log-pass-con"
                                                class="form-control"
                                                type="password"
                                                v-model="passCon"
                                                required
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <recaptcha ref="recaptcha" @verify="onVerify" :sitekey="key" />
                                    <small
                                        v-for="(i, key) in errors.recaptcha"
                                        :key="key"
                                        class="text-danger"
                                    >
                                        {{ i }}
                                        <br />
                                    </small>
                                </div>
                                <button class="btn btn-primary" :disabled="clicked === true">
                                    <fa icon="sign-in-alt" />
                                    {{ ' Daftar' }}
                                </button>
                                <fa
                                    v-if="clicked"
                                    icon="spinner"
                                    spin
                                    size="lg"
                                    class="text-primary"
                                />
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
        key: process.env.MIX_SITE_KEY,
        name: "",
        email: "",
        pass: "",
        passCon: "",
        recaptcha: "",
        has_error: false,
        errors: {},
        clicked: false
    }),
    methods: {
        onVerify(resp) {
            this.recaptcha = resp;
        },
        register() {
            this.clicked = true;

            this.$auth.register({
                data: {
                    name: this.name,
                    email: this.email,
                    password: this.pass,
                    password_confirmation: this.passCon,
                    recaptcha: this.recaptcha
                },
                success: () => {
                    this.$session.set("notif", true);
                    this.$router.push({
                        name: "login",
                        params: {
                            successRegistrationRedirect: true
                        }
                    });
                    this.clicked = false;
                    this.$refs.recaptcha.reset();
                },
                error: resp => {
                    console.log(resp.response);
                    this.has_error = true;
                    this.errors = resp.response.data.errors || {};
                    this.clicked = false;
                    this.$refs.recaptcha.reset();
                }
            });
        }
    }
};
</script>
