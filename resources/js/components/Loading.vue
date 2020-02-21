<template>
    <div>
        <div v-if="detail" class="card mb-3">
            <div class="card-header border-0 custom-bg-none">
                <div class="row pb-0">
                    <div class="col-1 text-center p-0">
                        <fa class="text-lighten" icon="caret-up" size="2x" />
                        <p class="p-0 m-0">
                            <fa class="text-lighten" icon="circle" size="lg" />
                        </p>
                        <fa class="text-lighten" icon="caret-down" size="2x" />
                    </div>
                    <div class="col-11">
                        <div class="pt-4 bg-lighten rounded-pill"></div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div v-for="(i, key) in 3" :key="key" class="pt-4 mb-2 bg-lighten rounded-pill"></div>
                <div class="pt-4 mt-2 bg-lighten rounded-pill col-6"></div>
            </div>
            <div class="card-footer border-0 custom-bg-none">
                <div class="row">
                    <div class="col-8">
                        <div class="col-6 bg-lighten rounded-pill pt-4"></div>
                        <div class="col-3 bg-lighten rounded-pill pt-4 mt-2"></div>
                    </div>
                    <div class="col-4">
                        <div class="row">
                            <div class="col-9">
                                <div class="bg-lighten rounded-pill pt-4 mt-2"></div>
                            </div>
                            <div class="col-3 px-0">
                                <fa class="text-lighten" icon="circle" size="3x" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-else class="card mb-3">
            <div class="card-header border-0 custom-bg-none">
                <div class="row pb-0">
                    <div class="col-1 text-center p-0">
                        <fa class="text-lighten" icon="caret-up" size="2x" />
                        <p class="p-0 m-0">
                            <fa class="text-lighten" icon="circle" size="lg" />
                        </p>
                        <fa class="text-lighten" icon="caret-down" size="2x" />
                    </div>
                    <div class="col-11">
                        <div class="row">
                            <div class="col-9">
                                <div class="pt-4 bg-lighten rounded-pill"></div>
                                <div
                                    v-if="rep(reply)"
                                    class="pt-2 mt-2 bg-lighten rounded-pill col-3 ml-1"
                                ></div>
                            </div>
                            <div v-if="rep(reply)" class="col-3">
                                <div class="pt-4 bg-lighten rounded-pill"></div>
                                <div
                                    class="float-right pt-2 mt-2 bg-lighten rounded-pill col-3 mr-1"
                                ></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div v-for="(i, key) in 3" :key="key" class="pt-4 mb-2 bg-lighten rounded-pill"></div>
                <div class="pt-4 mt-2 bg-lighten rounded-pill col-6"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["detail", "reply"],
    methods: {
        rep(r) {
            return r ? false : true;
        }
    }
};
</script>
