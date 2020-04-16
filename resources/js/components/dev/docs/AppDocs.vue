<template>
    <div>
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
</template>

<script>
export default {
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
        }
    }
};
</script>
