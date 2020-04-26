<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12">
                <div
                    v-for="(s, k) in src"
                    :key="k"
                    class="pt-4"
                    :id="k === 0 ? 'post' : (k === 1 ? 'article' : 'recommendation')"
                >
                    <h4>
                        <fa :icon="k === 0 ? 'code' : (k === 1 ? 'file-alt' : 'building')" />
                        {{ k === 0 ? ' Postingan' : (k === 1 ? ' Artikel' : ' Rekomendasi Perusahaan') }}
                    </h4>
                    <hr />
                    <div v-if="s.length" class="card pb-4">
                        <ul class="list-group list-group-flush">
                            <li
                                v-for="(i, key) in s"
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
                    <div v-else class="text-center text-secondary pb-4">
                        <h4>Hasil "{{ $route.query.q }}" tidak ditemukan</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="sticky-top pt-3">
                    <div class="card mb-3">
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
                    <div class="card">
                        <div v-if="src[0].length || src[1].length || src[2].length" class="card">
                            <div class="card-header text-center">Urutkan</div>
                            <ul class="list-group list-group-flush">
                                <li
                                    v-for="(i, key) in sort"
                                    :key="key"
                                    class="list-group-item custom-hover"
                                    :class="key === sortKey ? 'text-primary' : ''"
                                    @click="orderBy(key)"
                                >
                                    <fa :icon="i.icon" size="lg" />
                                    {{ ' ' + i.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        src: [[], [], []],
        sortKey: 0,
        sort: [
            { name: "Terbaru", icon: "sort-amount-up" },
            { name: "Terakhir", icon: "sort-amount-up-alt" },
            { name: "Judul A-Z", icon: "sort-alpha-up" },
            { name: "Judul Z-A", icon: "sort-alpha-down" },
            { name: "Vote Terbesar", icon: "sort-numeric-up-alt" },
            { name: "Vote Terkecil", icon: "sort-numeric-up" }
        ]
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
                    this.orderBy(0);
                })
                .catch(err => console.error(err.response));
        },
        orderBy(key) {
            this.sortKey = key;
            switch (key) {
                case 0:
                    this.src.forEach(s =>
                        s.sort((a, b) =>
                            a.id < b.id ? 1 : b.id < a.id ? -1 : 0
                        )
                    );
                    break;
                case 1:
                    this.src.forEach(s =>
                        s.sort((a, b) =>
                            a.id > b.id ? 1 : b.id > a.id ? -1 : 0
                        )
                    );
                    break;
                case 2:
                    this.src.forEach(s =>
                        s.sort((a, b) =>
                            a.title > b.title ? 1 : b.title > a.title ? -1 : 0
                        )
                    );
                    break;
                case 3:
                    this.src.forEach(s =>
                        s.sort((a, b) =>
                            a.title < b.title ? 1 : b.title < a.title ? -1 : 0
                        )
                    );
                    break;
                case 4:
                    this.src.forEach(s =>
                        s.sort((a, b) =>
                            a.votes < b.votes ? 1 : b.votes < a.votes ? -1 : 0
                        )
                    );
                    break;
                case 5:
                    return this.src.forEach(s =>
                        s.sort((a, b) =>
                            a.votes > b.votes ? 1 : b.votes > a.votes ? -1 : 0
                        )
                    );
                    break;

                default:
                    break;
            }
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