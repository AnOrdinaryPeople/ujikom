<template>
    <div class="container">
        <div v-if="n.length" class="mb-4">
            <button class="btn btn-success" @click="allRead()">
                <fa icon="check-double" />
                {{ ' Baca Semua' }}
            </button>
            <button class="btn btn-danger" @click="allDel()">
                <fa icon="trash-alt" />
                {{ ' Hapus Semua' }}
            </button>
        </div>
        <div v-for="(i, key) in n" :key="key" class="input-group mb-2">
            <div class="input-group-prepend">
                <button class="btn btn-success" @click="read(i.id, key)" :disabled="i.read === 1">
                    <fa icon="check" />
                </button>
                <router-link v-if="i.sauce" class="btn btn-primary" :to="removeURL(i.sauce)">
                    <fa icon="link" />
                </router-link>
                <router-link
                    v-else
                    class="btn btn-warning"
                    title="Pesan dari admin"
                    :to="'/notification/'+i.id"
                >
                    <fa icon="exclamation-triangle" />
                </router-link>
            </div>
            <input
                class="form-control"
                :class="i.read === 0 ? 'bg-white' : 'bg-lighten'"
                type="text"
                v-model="i.desc"
                disabled
            />
            <div v-if="i.sauce" class="input-group-append">
                <button class="btn btn-danger" @click="del(i.id, key)">
                    <fa icon="trash" />
                </button>
            </div>
        </div>
        <infinite @infinite="scrollLoad">
            <div slot="spinner">
                <fa icon="spinner" spin size="2x" class="text-primary" />
            </div>
            <div slot="no-more" class="text-secondary">
                <fa icon="skull-crossbones" size="5x" />
                <h4 class="mt-4">Tidak ada notifikasi lagi..</h4>
                <h6 title="yeet" @mouseover="secret = true" @mouseleave="secret = false">
                    <span v-if="secret">(ノOωO )ノ⌒┻━┻</span>
                    <span v-else>┳━┳ノ( OωOノ)</span>
                </h6>
            </div>
            <div slot="no-results" class="text-secondary">
                <fa icon="sad-cry" size="5x" />
                <h4 class="mt-4">Tidak ada notifikasi..</h4>
                <h6 title="AAAAAA" @mouseover="secret = true" @mouseleave="secret = false">
                    <span v-if="secret">┻━┻ミ＼（≧ロ≦＼）</span>
                    <span v-else>┬─┬ノ(ಠ_ಠノ)</span>
                </h6>
            </div>
        </infinite>
    </div>
</template>

<script>
export default {
    data: () => ({
        n: [],
        skip: 0,
        secret: false,
        sauce: process.env.MIX_APP_URL
    }),
    mounted() {
        axios
            .post(`user/notif/${this.$auth.user().id}/${this.skip}`)
            .then(resp => {
                this.data = resp.data;
                this.skip += 10;
            });
    },
    methods: {
        scrollLoad($state) {
            axios
                .post(`/user/notif/${this.$auth.user().id}/${this.skip}`)
                .then(resp => {
                    if (resp.data.length) {
                        this.n.push(...resp.data);
                        this.skip += 10;
                        try {
                            $state.loaded();
                        } catch (e) {}
                    } else {
                        try {
                            $state.complete();
                        } catch (e) {}
                    }
                });
        },
        read(id, key) {
            axios.post(`user/not/read/${id}`).then(() => {
                this.$emit("appNotCount");
                this.n[key].read = 1;
            });
        },
        del(id, key) {
            axios
                .post(`user/not/del/${id}/${this.$auth.user().id}`)
                .then(resp => {
                    Vue.delete(this.n, key);
                    this.$parent.appNotifCounter = resp.data;
                });
        },
        allRead() {
            axios.post(`user/not/all/${this.$auth.user().id}`).then(() => {
                this.$emit("appNotCount");
                this.n.map(x => (x.read = 1));
            });
        },
        allDel() {
            axios.post(`user/not/alll/${this.$auth.user().id}`).then(() => {
                this.$emit("appNotCount");
                this.n = [];
            });
        },
        removeURL(str) {
            return str.replace(this.sauce, "");
        }
    }
};
</script>
