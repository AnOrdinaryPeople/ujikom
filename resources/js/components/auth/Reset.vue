<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 col-md-9 col-lg-6">
                <div v-if="loaded" class="card">
                    <div class="card-header">
                        <h4>Reset Password</h4>
                    </div>
                    <div id="icon-pass" class="card-img-top text-center">
                        <fa class="jello-horizontal mt-4" icon="user-lock" size="10x" />
                    </div>
                    <div class="card-body">
                        <form autocomplete="off" @submit.prevent="forgot()" method="post">
                            <div class="form-group">
                                <label for="log-pass">
                                    <fa icon="key" />
                                    {{ ' Password' }}
                                </label>
                                <input
                                    id="log-pass"
                                    class="form-control"
                                    :class="errors.length ? 'is-invalid' : ''"
                                    type="password"
                                    v-model="pass"
                                    placeholder="min. password length 8"
                                    title="min. password length 8"
                                    required
                                />
                                <small v-for="(i, key) in errors" :key="key" class="text-danger">
                                    {{ i }}
                                    <br />
                                </small>
                            </div>
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
                            <button class="btn btn-primary" :disabled="clicked === true">
                                <fa icon="sign-in-alt" />
                                {{ ' Kirim' }}
                            </button>
                            <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
                        </form>
                    </div>
                </div>
                <div v-else class="row align-items-center custom-h-100-vh">
                    <div v-if="fail" class="col-12 mx-auto text-center">
                        <h1 class="text-danger">Token tidak ditemukan atau sudah expire!</h1>
                        <router-link
                            class="text-decoration-none"
                            :to="$auth.check() ? '/home' : '/'"
                        >Kembali</router-link>
                    </div>
                    <div v-else class="col-12 mx-auto text-center">
                        <fa icon="spinner" spin size="5x" class="text-primary" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        loaded: false,
        fail: false,
        clicked: false,
        pass: "",
        passCon: "",
        token: "",
        errors: []
    }),
    mounted() {
        if (this.$route.query.token)
            axios
                .post("user/forgot/reset/check", {
                    token: this.$route.query.token
                })
                .then(resp => {
                    this.token = this.$route.query.token;
                    if (resp.data.count) {
                        this.loaded = true;
                    } else this.fail = true;
                });
    },
    methods: {
        forgot() {
            this.clicked = true;
            axios
                .post("user/forgot/reset", {
                    password: this.pass,
                    password_confirmation: this.passCon,
                    token: this.token
                })
                .then(resp => {
                    this.$session.set("notiff", true);
                    this.$router.push({ name: "login" });
                    this.this.clicked = false;
                })
                .catch(err => {
                    this.errors = err.response.data.password;
                    this.clicked = false;
                });
        }
    }
};
</script>
