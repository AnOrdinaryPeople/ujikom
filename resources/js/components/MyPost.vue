<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div v-if="post.length">
                    <div v-for="(i, key) in post" :key="key" class="card mb-3 custom-limit">
                        <div class="card-header border-0 custom-bg-none">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-9">
                                            {{ i.title | titleCase }}
                                            <br />
                                            <small>{{ i.name }}</small>
                                        </div>
                                        <div class="col-3 text-right">
                                            {{ i.created_at | dt }}
                                            <br />
                                            <small>{{ i.type == 1 ? 'Postingan' : (i.type == 2 ? 'Artikel' : 'Rekomendasi Perusahaan') }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-hover" @click="detail(i.id)">
                            <h5 v-if="i.type == 3" class="text-muted">{{ i.location }}</h5>
                            <markdown class="mb-body" :content="i.desc" />
                            <div class="custom-bottom"></div>
                        </div>
                        <div class="card-footer border-0 custom-bg-none">
                            <small class="text-muted">
                                <fa icon="check" />
                                {{ ' ' }}{{ i.votes | voteFilter }}{{ ' votes |' }}
                            </small>
                            <small class="text-muted">
                                <fa icon="comment-dots" />
                                {{ ' ' + i.total + ' komentar' }}
                            </small>
                            <div class="dropdown dropup float-right">
                                <button class="btn" data-toggle="dropdown">
                                    <fa icon="ellipsis-v" />
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <router-link class="dropdown-item" :to="`/home/edit/${i.id}`">
                                        <fa icon="pen" />
                                        {{ ' Edit' }}
                                    </router-link>
                                    <a
                                        class="dropdown-item"
                                        data-toggle="modal"
                                        data-target="#delete-post"
                                        href="#"
                                        @click.prevent="danger(key)"
                                    >
                                        <fa icon="trash" />
                                        {{ ' Hapus' }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <div v-if="!loaded" class="card mb-3">
                        <div class="card-header border-0 bg-white">
                            <div class="row">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-9">
                                            <div class="pt-4 bg-lighten rounded-pill"></div>
                                            <div
                                                class="pt-2 mt-2 bg-lighten rounded-pill col-3 ml-1"
                                            ></div>
                                        </div>
                                        <div class="col-3">
                                            <div class="pt-4 bg-lighten rounded-pill"></div>
                                            <div
                                                class="float-right pt-2 mt-2 bg-lighten rounded-pill col-3 mr-1"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div
                                v-for="(i, key) in 3"
                                :key="key"
                                class="pt-4 mb-2 bg-lighten rounded-pill"
                            ></div>
                            <div class="pt-4 mt-2 bg-lighten rounded-pill col-6"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <infinite @infinite="scrollLoad">
            <div slot="spinner">
                <fa icon="spinner" spin size="2x" class="text-primary" />
            </div>
            <div slot="no-more" class="text-secondary">
                <fa icon="skull-crossbones" size="5x" />
                <h4 class="mt-4">Kamu sudah ke tempat yang paling dalam!</h4>
                <h6 title="yeet" @mouseover="secret = true" @mouseleave="secret = false">
                    <span v-if="secret">(ノOωO )ノ⌒┻━┻</span>
                    <span v-else>┳━┳ノ( OωOノ)</span>
                </h6>
            </div>
            <div slot="no-results" class="text-secondary">
                <fa icon="sad-cry" size="5x" />
                <h4 class="mt-4">Tidak ada postingan..</h4>
                <h6 title="AAAAAA" @mouseover="secret = true" @mouseleave="secret = false">
                    <span v-if="secret">┻━┻ミ＼（≧ロ≦＼）</span>
                    <span v-else>┬─┬ノ(ಠ_ಠノ)</span>
                </h6>
            </div>
        </infinite>
        <modal idd="delete-post" type="danger" :ttl="p.ttl" :sauce="`user/destroy/${p.id}`" />
    </div>
</template>

<script>
export default {
    data: () => ({
        post: [],
        skip: 0,
        secret: false,
        loaded: false,
        p: {
            id: 0,
            ttl: ""
        }
    }),
    methods: {
        scrollLoad($state) {
            axios
                .post(`/user/mine/${this.$auth.user().id}/${this.skip}`)
                .then(resp => {
                    if (resp.data.length) {
                        this.post.push(...resp.data);
                        this.skip += 10;
                        try {
                            $state.loaded();
                        } catch (e) {}
                    } else {
                        try {
                            $state.complete();
                        } catch (e) {}
                    }
                    this.loaded = true;
                });
        },
        danger(key) {
            this.p = {
                id: this.post[key].id,
                ttl: this.post[key].title
            };
        },
        detail(id) {
            this.$router.push(`/home/detail/${id}`);
        }
    },
    filters: {
        titleCase(str) {
            return str.replace(/\w\S*/g, txt => {
                return (
                    txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase()
                );
            });
        },
        dt(str) {
            return new Date(str).toLocaleString("id-ID", {
                dateStyle: "medium"
            });
        },
        voteFilter(n) {
            const si = [
                    { value: 1e18, symbol: "E" },
                    { value: 1e15, symbol: "P" },
                    { value: 1e12, symbol: "T" },
                    { value: 1e9, symbol: "G" },
                    { value: 1e6, symbol: "M" },
                    { value: 1e3, symbol: "k" }
                ],
                msi = [
                    { value: -1e18, symbol: "E" },
                    { value: -1e15, symbol: "P" },
                    { value: -1e12, symbol: "T" },
                    { value: -1e9, symbol: "G" },
                    { value: -1e6, symbol: "M" },
                    { value: -1e3, symbol: "k" }
                ];

            for (var i = 0; i < si.length; i++) {
                if (n >= si[i].value)
                    return (
                        (n / si[i].value).toFixed(1).replace(/\.?0+$/, "") +
                        si[i].symbol
                    );
                else if (n <= msi[i].value)
                    return (
                        "-" +
                        (n / msi[i].value).toFixed(1).replace(/\.?0+$/, "") +
                        msi[i].symbol
                    );
            }
            return n;
        }
    }
};
</script>
