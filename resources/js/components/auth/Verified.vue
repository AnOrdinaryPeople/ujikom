<template>
    <div class="container-fluid">
        <div class="row align-items-center custom-h-100-vh">
            <div v-if="loaded" class="col-6 mx-auto text-center">
                <h1>Email kamu sudah terverifikasi!</h1>
                <router-link
                    class="text-decoration-none"
                    :to="$auth.check() ? '/home' : '/'"
                >Kembali</router-link>
            </div>
            <div v-else class="col-6 mx-auto">
                <div v-if="fail" class="text-center">
                    <h1 class="text-danger">Token tidak ditemukan!</h1>
                    <router-link
                        class="text-decoration-none"
                        :to="$auth.check() ? '/home' : '/'"
                    >Kembali</router-link>
                </div>
                <div v-else class="text-center">
                    <fa icon="spinner" spin size="5x" class="text-primary" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        loaded: false,
        fail: false
    }),
    mounted() {
        if (this.$route.query.token)
            axios
                .post("/user/verify", {
                    token: this.$route.query.token
                })
                .then(resp => {
                    if (resp.data.count) this.loaded = true;
                    else this.fail = true;
                })
                .catch(err => console.error(err.response));
    }
};
</script>
