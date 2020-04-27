<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 pt-3">
                <div v-if="loaded" class="card">
                    <div class="card-header border-0 custom-bg-none">
                        <div class="row">
                            <div class="col-1 text-center text-lighten p-0">
                                <fa icon="caret-up" size="2x" />
                                <p
                                    class="p-0 m-0 text-secondary"
                                    :title="p.votes"
                                >{{ p.votes | voteFilter }}</p>
                                <fa icon="caret-down" size="2x" />
                            </div>
                            <div class="col-10">
                                <h4 class="mb-0">{{ p.title | titleCase }}</h4>
                                <small
                                    class="text-muted"
                                >{{ p.created_at !== p.updated_at ? '(edited)' : '' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="warn.title !== ''" class="alert alert-warning">
                            <h4 class="mb-0">{{ warn.title }}</h4>
                            <small>Peringatan dari {{ warn.name }}</small>
                            <markdown
                                class="mt-2 mb-body custom-text-warning"
                                :content="warn.desc"
                            />
                        </div>
                        <h5 v-if="p.type == 3" class="text-muted">{{ p.location }}</h5>
                        <markdown class="mb-body" :content="p.desc" />
                    </div>
                    <div class="card-footer custom-bg-none border-top">
                        <div class="container">
                            <div class="row">
                                <div class="col-6">
                                    <span :title="p.created_at">{{ p.created_at | dt }}</span>
                                    <br />
                                    <small>{{ p.type == 1 ? 'Postingan' : (p.type == 2 ? 'Artikel' : 'Rekomendasi Perusahaan') }}</small>
                                </div>
                                <div class="col-5 px-0 mx-0 pt-2 text-right">{{ p.name }}</div>
                                <div class="col-1 custom-img">
                                    <img class="rounded-circle img-responsive" :src="p.avatar" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div v-else>
                    <post-load :detail="true" />
                </div>
                <h4 id="answer-top" class="mt-4">{{ p.type == 1 ? 'Jawaban' : 'Komentar' }}</h4>
                <div v-if="best" id="detail-best" class="card mb-4 border-success">
                    <div class="card-header border-0 custom-bg-none">
                        <div class="row pb-0">
                            <div class="col-1 text-center text-lighten p-0">
                                <fa icon="caret-up" size="2x" />
                                <p
                                    class="p-0 m-0 text-secondary"
                                    :title="best.votes"
                                >{{ best.votes | voteFilter }}</p>
                                <fa icon="caret-down" size="2x" />
                            </div>
                            <div class="col-10">
                                <div class="row">
                                    <div class="col-9">
                                        <div class="row">
                                            <div class="col-1 custom-img text-center">
                                                <img
                                                    class="rounded-circle img-responsive"
                                                    :src="best.avatar"
                                                />
                                            </div>
                                            <div class="col-11 pl-4 pt-2">
                                                {{ best.name + ' ' }}
                                                <small
                                                    class="text-muted font-italic"
                                                >/ {{ best.created_at | dt }} {{ best.created_at !== best.updated_at ? '(edited)' : '' }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3 text-right">
                                        <fa class="text-success" icon="check" size="2x" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-1">
                                <div class="dropdown float-right">
                                    <button class="btn" data-toggle="dropdown">
                                        <fa icon="ellipsis-v" />
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        <a
                                            class="dropdown-item"
                                            href="#"
                                            @click.prevent="open('best')"
                                        >
                                            <fa icon="trash" />
                                            {{ ' Hapus' }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <markdown class="mb-body" :content="best.desc" />
                        <div v-if="child.length" class="container mt-4">
                            <hr />
                            <div v-for="(i, k) in child" :key="k" class="card border-0">
                                <div class="card-body pt-0">
                                    <div class="row">
                                        <div class="col-1 custom-img text-center">
                                            <img
                                                class="rounded-circle img-responsive"
                                                :src="i.avatar"
                                            />
                                        </div>
                                        <div class="col-10 pl-4 pt-2">
                                            {{ i.name + ' ' }}
                                            <small
                                                class="text-muted font-italic"
                                            >/ {{ i.created_at | dt }} {{ i.created_at !== i.updated_at ? '(edited)' : '' }}</small>
                                        </div>
                                        <div class="col-1">
                                            <div class="dropdown float-right">
                                                <button class="btn" data-toggle="dropdown">
                                                    <fa icon="ellipsis-v" />
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a
                                                        class="dropdown-item"
                                                        href="#"
                                                        @click.prevent="open('bestChild', k)"
                                                    >
                                                        <fa icon="trash" />
                                                        {{ ' Hapus' }}
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <markdown class="mb-body mt-2" :content="i.desc" />
                                    <div class="text-right mt-4 text-lighten" :title="i.votes">
                                        <fa icon="caret-up" size="lg" />
                                        <span
                                            class="p-0 m-0 text-secondary"
                                            :title="i.votes"
                                        >{{ i.votes | voteFilter }}</span>
                                        <fa icon="caret-down" size="lg" />
                                    </div>
                                    <hr />
                                </div>
                            </div>
                        </div>
                        <div class="text-center">
                            <button
                                v-if="child.length >= 5 && bestCanLoad"
                                class="btn btn-outline-secondary"
                                @click="loadmore('best', best.id)"
                                :disabled="clicked"
                            >
                                <span v-if="clicked">
                                    <fa icon="spinner" spin size="lg" class="text-secondary" />
                                </span>
                                <span v-else>Load more</span>
                            </button>
                        </div>
                    </div>
                </div>
                <hr />
                <div id="detail-answer">
                    <div v-if="r.data.length">
                        <div
                            v-for="(i, key) in r.data"
                            :key="key"
                            :id="'reply-'+i.id"
                            class="card mb-2"
                        >
                            <div class="card-header border-0 custom-bg-none">
                                <div class="row pb-0">
                                    <div class="col-1 text-center text-lighten p-0">
                                        <fa icon="caret-up" size="2x" />
                                        <p
                                            class="p-0 m-0 text-secondary"
                                            :title="i.votes"
                                        >{{ i.votes | voteFilter }}</p>
                                        <fa icon="caret-down" size="2x" />
                                    </div>
                                    <div class="col-10">
                                        <div class="row">
                                            <div class="col-9">
                                                <div class="row">
                                                    <div class="col-1 custom-img text-center">
                                                        <img
                                                            class="rounded-circle img-responsive"
                                                            :src="i.avatar"
                                                        />
                                                    </div>
                                                    <div class="col-10 pl-4 pt-2">
                                                        {{ i.name + ' ' }}
                                                        <small
                                                            class="text-muted font-italic"
                                                        >/ {{ i.created_at | dt }} {{ i.created_at !== i.updated_at ? '(edited)' : '' }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-1">
                                        <div class="dropdown float-right">
                                            <button class="btn" data-toggle="dropdown">
                                                <fa icon="ellipsis-v" />
                                            </button>
                                            <div class="dropdown-menu dropdown-menu-right">
                                                <a
                                                    class="dropdown-item"
                                                    href="#"
                                                    @click.prevent="open('reply', key)"
                                                >
                                                    <fa icon="trash" />
                                                    {{ ' Hapus' }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <markdown class="mb-body" :content="i.desc" />
                                <div v-if="i.has_child.length" class="container mt-4">
                                    <hr />
                                    <div
                                        v-for="(j, k) in i.has_child"
                                        :key="k"
                                        class="card border-0"
                                    >
                                        <div class="card-body pt-0">
                                            <div class="row">
                                                <div class="col-1 custom-img text-center">
                                                    <img
                                                        class="rounded-circle img-responsive"
                                                        :src="j.avatar"
                                                    />
                                                </div>
                                                <div class="col-10 pl-4 pt-2">
                                                    {{ j.name + ' ' }}
                                                    <small
                                                        class="text-muted font-italic"
                                                    >/ {{ j.created_at | dt }} {{ j.created_at !== j.updated_at ? '(edited)' : '' }}</small>
                                                </div>
                                                <div class="col-1">
                                                    <div class="dropdown float-right">
                                                        <button class="btn" data-toggle="dropdown">
                                                            <fa icon="ellipsis-v" />
                                                        </button>
                                                        <div
                                                            class="dropdown-menu dropdown-menu-right"
                                                        >
                                                            <a
                                                                class="dropdown-item"
                                                                href="#"
                                                                @click.prevent="open('replyChild', key, k)"
                                                            >
                                                                <fa icon="trash" />
                                                                {{ ' Hapus' }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <markdown class="mb-body mt-2" :content="j.desc" />
                                            <div class="text-right text-lighten" :title="j.votes">
                                                <fa icon="caret-up" size="lg" />
                                                <span
                                                    class="p-0 m-0 text-secondary"
                                                    :title="j.votes"
                                                >{{ j.votes | voteFilter }}</span>
                                                <fa icon="caret-down" size="lg" />
                                            </div>
                                            <hr />
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button
                                        v-if="i.has_child.length >= 5 && replySkip[key].canLoad"
                                        class="btn btn-outline-secondary"
                                        @click="loadmore('rep', i.id, key)"
                                        :disabled="clicked"
                                    >
                                        <span v-if="clicked">
                                            <fa
                                                icon="spinner"
                                                spin
                                                size="lg"
                                                class="text-secondary"
                                            />
                                        </span>
                                        <span v-else>Load more</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <div v-if="loaded" class="text-center text-secondary">
                            <fa class="mb-2" icon="sad-cry" size="5x" />
                            <h1>Tidak ada {{ p.type == 1 ? 'jawaban' : 'komentar' }}</h1>
                        </div>
                        <div v-else>
                            <post-load :reply="true" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 pt-3">
                <div class="sticky-top pt-3">
                    <div v-if="r.data.length" class="card mb-3">
                        <div
                            class="card-header text-center"
                        >Urutkan {{ p.type == 1 ? 'Jawaban' : 'Komentar' }}</div>
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
                    <paginate :data="r" @pagination-change-page="changePage" />
                </div>
            </div>
        </div>
        <admin-modal
            idd="reply-delete"
            size="modal-lg"
            title="Konfirmasi Hapus"
            icon="exclamation-triangle"
            bg-title="bg-danger text-light"
            :is-form="true"
        >
            <div slot="body">
                <h5>Hapus komentar ini?</h5>
                <div class="form-group">
                    <label>Beri pesan ke {{ data.name }} kenapa komentar ini harus dihapus</label>
                    <admin-invut @send="send" ref="invut" />
                </div>
            </div>
        </admin-modal>
    </div>
</template>

<script>
export default {
    data: () => ({
        loaded: false,
        p: {
            id: 0,
            type: 1,
            votes: 0,
            title: "",
            name: "",
            created_at: "2020-01-01",
            location: "",
            desc: "",
            avatar: "",
            temp: 0
        },
        r: { data: [] },
        best: 0,
        child: [],
        warn: {
            id: 0,
            title: "",
            desc: "",
            name: "",
            user_id: 0
        },
        bestSkip: 5,
        bestCanLoad: true,
        replySkip: [],
        clicked: false,
        sort: [
            { name: "Terbaru", icon: "sort-amount-up" },
            { name: "Terakhir", icon: "sort-amount-up-alt" },
            { name: "Vote Terbesar", icon: "sort-numeric-up-alt" },
            { name: "Vote Terkecil", icon: "sort-numeric-up" }
        ],
        sortKey: 0,
        data: {}
    }),
    mounted() {
        axios.post(`user/home/detail/${this.$route.params.id}/0`).then(resp => {
            this.p = resp.data.post;
            this.best = resp.data.best;
            this.child = resp.data.child_best;
            resp.data.warn ? (this.warn = resp.data.warn) : "";
            this.loaded = true;
        });

        this.$route.query.page !== undefined
            ? this.changePage(this.$route.query.page)
            : this.changePage();
    },
    methods: {
        loadmore(type, id, key = 0) {
            this.clicked = true;
            axios
                .post(
                    `user/home/detail/child/more/${id}/${
                        type === "best"
                            ? this.bestSkip
                            : this.replySkip[key].skip
                    }`
                )
                .then(resp => {
                    if (resp.data.length) {
                        if (type === "best") {
                            this.child.push(...resp.data);
                            this.bestSkip += 5;
                        } else {
                            this.r.data[key].has_child.push(...resp.data);
                            this.replySkip[key].skip += 5;
                        }
                    } else {
                        if (type === "best") this.bestCanLoad = false;
                        else this.replySkip[key].canLoad = false;
                    }
                    this.clicked = false;
                });
        },
        changePage(page = 1) {
            axios
                .post(`user/reply/${this.$route.params.id}/0?page=${page}`)
                .then(resp => {
                    this.r = resp.data.reply;

                    this.$router
                        .replace({
                            query: {
                                ...this.$route.query,
                                page: page
                            }
                        })
                        .catch(err => {});
                    this.orderBy(this.sortKey);
                    if (this.r.data.length)
                        for (var i = 0; i < this.r.data.length; i++)
                            Vue.set(this.replySkip, i, {
                                canLoad: true,
                                skip: 5
                            });
                })
                .catch(err => console.error(err));
        },
        orderBy(key) {
            this.sortKey = key;
            switch (key) {
                case 0:
                    return this.r.data.sort((a, b) =>
                        a.id < b.id ? 1 : b.id < a.id ? -1 : 0
                    );
                    break;
                case 1:
                    return this.r.data.sort((a, b) =>
                        a.id > b.id ? 1 : b.id > a.id ? -1 : 0
                    );
                    break;
                case 2:
                    return this.r.data.sort((a, b) =>
                        a.votes < b.votes ? 1 : b.votes < a.votes ? -1 : 0
                    );
                    break;
                case 3:
                    return this.r.data.sort((a, b) =>
                        a.votes > b.votes ? 1 : b.votes > a.votes ? -1 : 0
                    );
                    break;

                default:
                    break;
            }
        },
        open(type, key = 0, child = 0) {
            switch (type) {
                case "best":
                    this.data = this.best;
                    break;
                case "bestChild":
                    this.data = this.child[key];
                    break;
                case "reply":
                    this.data = this.r.data[key];
                    break;
                case "replyChild":
                    this.data = this.r.data[key].has_child[child];
                    break;

                default:
                    break;
            }
            $("#reply-delete").modal();
        },
        send(data) {
            axios
                .post(
                    `admin/posts/reply/${this.data.id}/${this.data.user_id}`,
                    { desc: data }
                )
                .then(() => {
                    this.$refs.invut.reset();
                    $("#reply-delete").modal("hide");

                    this.$route.query.page !== undefined
                        ? this.changePage(this.$route.query.page)
                        : this.changePage();
                    axios
                        .post(`user/home/detail/${this.$route.params.id}/0`)
                        .then(resp => {
                            this.best = resp.data.best;
                            this.child = resp.data.child_best;
                        });
                });
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
