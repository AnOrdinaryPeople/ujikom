<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 pt-3">
                <h4 id="post">Postingan</h4>
                <hr />
                <div v-if="src[0].length" class="card mb-4">
                    <ul class="list-group list-group-flush">
                        <li
                            v-for="(i, key) in src[0]"
                            :key="key"
                            class="list-group-item custom-hover"
                            @click="open(i.id)"
                        >
                            <h4>{{ i.title | titleCase }}</h4>
                            <small class="text-muted">{{ i.name }} | {{ i.created_at | dt }}</small>
                            <br />
                            <small class="text-secondary">
                                <fa icon="comment-dots" />
                                {{ ' ' + i.total + ' | ' }}
                                <fa icon="check" />
                                {{ ' ' + i.votes }}
                            </small>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-center text-secondary">
                    <h4>Hasil {{ $route.query.q }} untuk postingan tidak ditemukan</h4>
                </div>
                <h4 id="article">Artikel</h4>
                <hr />
                <div v-if="src[1].length" class="card mb-4">
                    <ul class="list-group list-group-flush">
                        <li
                            v-for="(i, key) in src[1]"
                            :key="key"
                            class="list-group-item custom-hover"
                            @click="open(i.id)"
                        >
                            <h4>{{ i.title | titleCase }}</h4>
                            <small class="text-muted">{{ i.name }} | {{ i.created_at | dt }}</small>
                            <br />
                            <small class="text-secondary">
                                <fa icon="comment-dots" />
                                {{ ' ' + i.total + ' | ' }}
                                <fa icon="check" />
                                {{ ' ' + i.votes }}
                            </small>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-center text-secondary">
                    <h4>Hasil {{ $route.query.q }} untuk artikel tidak ditemukan</h4>
                </div>
                <h4 id="recommendation">Rekomendasi Perusahaan</h4>
                <hr />
                <div v-if="src[2].length" class="card">
                    <ul class="list-group list-group-flush">
                        <li
                            v-for="(i, key) in src[2]"
                            :key="key"
                            class="list-group-item custom-hover"
                            @click="open(i.id)"
                        >
                            <h4>{{ i.title | titleCase }}</h4>
                            <small class="text-muted">{{ i.name }} | {{ i.created_at | dt }}</small>
                            <br />
                            <small class="text-secondary">
                                <fa icon="comment-dots" />
                                {{ ' ' + i.total + ' | ' }}
                                <fa icon="check" />
                                {{ ' ' + i.votes }}
                            </small>
                        </li>
                    </ul>
                </div>
                <div v-else class="text-center text-secondary">
                    <h4>Hasil {{ $route.query.q }} untuk rekomendasi perusahaan tidak ditemukan</h4>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="sticky-top pt-3">
                    <div class="card">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item custom-hover" @click="to('post')">
                                <fa icon="hashtag" />
                                {{ ' Postingan' }}
                            </li>
                            <li class="list-group-item custom-hover" @click="to('article')">
                                <fa icon="hashtag" />
                                {{ ' Artikel' }}
                            </li>
                            <li class="list-group-item custom-hover" @click="to('recommendation')">
                                <fa icon="hashtag" />
                                {{ ' Rekomendasi Perusahaan' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        src: {
            0: [],
            1: [],
            2: []
        }
    }),
    mounted() {
        this.search(this.$route.query.q);
    },
    methods: {
        open(id) {
            this.$router.push({ path: "/home/detail/" + id });
        },
        to(id) {
            document.getElementById(id).scrollIntoView();
        },
        search(query) {
            axios
                .post("user/search/", {
                    q: query
                })
                .then(resp => {
                    this.src = resp.data;
                })
                .catch(err => console.error(err.response));
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
    },
    watch: {
        "$route.query.q": {
            handler(q) {
                this.search(q);
            }
        },
        deep: true
    }
};
</script>