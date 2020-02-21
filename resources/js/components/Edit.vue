<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 pt-3">
                <invut :sauce="`/user/update/${this.$route.params.id}`" pg="edit" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        data: {
            type: null,
            title: "",
            location: "",
            desc: ""
        }
    })
};
</script>
