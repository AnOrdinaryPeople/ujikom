<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div class="card">
                    <div v-if="desc !== ''" class="card-body">
                        <h5 class="card-title">Pesan dari Admin</h5>
                        <markdown class="mb-body" :content="desc" />
                    </div>
                    <div v-else class="card-body text-center">
                        <fa icon="spinner" spin size="2x" class="text-primary" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        desc: ""
    }),
    mounted() {
        axios
            .post(
                `user/not/detail/${this.$route.params.id}/${
                    this.$auth.user().id
                }`
            )
            .then(resp => (this.desc = resp.data.desc));
    }
};
</script>