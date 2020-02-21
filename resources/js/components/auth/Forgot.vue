<template>
    <div class="jumbroton jumbroton-fluid mt-0">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 col-md-9 col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h4>Lupa Password</h4>
                        </div>
                        <div class="card-img-top custom-box">
                            <fa id="ask-1" class="scale-up-center" icon="question" size="lg" />
                            <fa id="ask-2" class="scale-up-center" icon="question" size="lg" />
                            <fa id="ask-3" class="scale-up-center" icon="question" size="lg" />
                            <fa id="ask-4" class="scale-up-center" icon="question" size="lg" />
                            <div class="custom-img-250 text-center mt-4">
                                <img class="fa-spin" :src="sauce + '/img/thinking.svg'" />
                            </div>
                        </div>
                        <div class="card-body">
                            <div
                                v-if="done"
                                class="alert alert-success"
                            >Permintaan sudah dikirim ke email yang dituju</div>
                            <form autocomplete="off" @submit.prevent="forgot()" method="post">
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
                                        autofocus
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
                                    {{ ' Kirim' }}
                                </button>
                                <fa
                                    v-if="clicked"
                                    icon="spinner"
                                    spin
                                    size="lg"
                                    class="text-primary"
                                />
                            </form>
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
        recaptcha: "",
        key: process.env.MIX_SITE_KEY,
        errors: {},
        clicked: false,
        done: false,
        sauce: process.env.MIX_APP_URL,
        interval: null
    }),
    mounted() {
        this.loop();
        this.interval = setInterval(() => {
            this.loop();
        }, 2000);
    },
    methods: {
        onVerify(resp) {
            this.recaptcha = resp;
        },
        forgot() {
            this.clicked = true;
            axios
                .post("/user/forgot", {
                    email: this.email,
                    recaptcha: this.recaptcha
                })
                .then(resp => {
                    this.done = true;
                    this.clicked = false;
                })
                .catch(err => {
                    this.errors = err.response.data;
                    this.clicked = false;
                });
        },
        loop() {
            for (var i = 1; i <= 4; i++) {
                var doc = document.getElementById("ask-" + i);

                doc.style.transform = `rotate(${Math.floor(
                    Math.random() * 360
                ) - 360}deg) scale(0)`;
                doc.style.top = this.rng(5, 75) + "%";
                doc.style.left = this.rng(5, 75) + "%";
            }
        },
        rng(min, max) {
            return Math.floor(Math.random() * max) + min;
        }
    },
    destroyed() {
        clearInterval(this.interval);
    }
};
</script>
