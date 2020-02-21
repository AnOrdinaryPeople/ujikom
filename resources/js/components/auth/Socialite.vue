<template>
    <div>
        <hr />
        <button class="btn btn-google btn-block" @click="authProvider('google')">
            <fa :icon="['fab', 'google']" />
            {{ ' Login dengan Google' }}
        </button>
        <button class="btn btn-facebook btn-block" @click="authProvider('facebook')">
            <fa :icon="['fab', 'facebook-f']" />
            {{ ' Login dengan Facebook' }}
        </button>
    </div>
</template>

<script>
export default {
    methods: {
        authProvider(prov) {
            axios({
                method: "GET",
                url: `/auth/${prov}/redirect`,
                headers: { Authorization: null }
            })
                .then(resp => {
                    window.location.href = resp.data.uri;
                })
                .catch(err => {
                    console.log(err);
                });
        }
    }
};
</script>
