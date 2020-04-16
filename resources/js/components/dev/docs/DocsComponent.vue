<template>
    <div>
        <div v-for="(i, key) in data" :key="key" class="card custom-card-max-h-500 mb-4">
            <div class="card-body">
                <h1 v-if="i.title !== ''" class="card-title border-bottom p-2">{{ i.title }}</h1>
                <div class="row">
                    <div :class="i.right === '' ? 'col-12' : 'col-6'">
                        <markdown class="mb-body card-text" :content="i.left" />
                    </div>
                    <div v-if="i.right !== ''" class="col-6">
                        <markdown class="mb-body card-text" :content="i.right" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["data"]
};
</script>
