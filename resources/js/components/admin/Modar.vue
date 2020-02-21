<template>
    <div :id="idd" class="modal fade" tabindex="-1">
        <div class="modal-dialog" :class="size">
            <div class="modal-content">
                <div class="modal-header" :class="bgTitle">
                    <h5>
                        <fa v-if="icon" :icon="icon" />
                        {{ ' ' + title }}
                    </h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div v-if="isForm">
                    <form autocomplete="off" method="post" @submit.prevent="send">
                        <div class="modal-body">
                            <slot name="body"></slot>
                        </div>
                        <div v-if="hasFooter" class="modal-footer">
                            <button class="btn btn-secondary" data-dismiss="modal">Batalkan</button>
                            <slot name="footer"></slot>
                        </div>
                    </form>
                </div>
                <div v-else>
                    <div class="modal-body">
                        <slot name="body"></slot>
                    </div>
                    <div v-if="hasFooter" class="modal-footer">
                        <slot name="footer"></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["idd", "title", "bgTitle", "icon", "size", "isForm"],
    methods: {
        send() {
            this.$emit("send");
        }
    },
    computed: {
        hasFooter() {
            return !!this.$slots["footer"];
        }
    }
};
</script>
