<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 pt-3">
                <div v-if="$auth.check()" class="card mb-3">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs text-center row">
                            <li class="nav-item col-4">
                                <a
                                    class="nav-link"
                                    :class="i === 0 ? 'active' : ''"
                                    href="#"
                                    @click.prevent="page(0)"
                                >Input</a>
                            </li>
                            <li class="nav-item col-4">
                                <a
                                    class="nav-link"
                                    :class="i === 1 ? 'active' : ''"
                                    href="#"
                                    @click.prevent="page(1)"
                                >Hasil</a>
                            </li>
                            <li class="nav-item col-4">
                                <a
                                    class="nav-link"
                                    :class="i === 2 ? 'active' : ''"
                                    href="#"
                                    @click.prevent="page(2)"
                                >Format</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div v-if="i === 0">
                            <div v-if="err.length" class="alert alert-danger">
                                <ul>
                                    <li v-for="(i, key) in err" :key="key">{{ i }}</li>
                                </ul>
                            </div>
                            <form autocomplete="off" @submit.prevent="submited()" method="post">
                                <div class="form-inline mb-2">
                                    <div class="custom-control custom-radio">
                                        <input
                                            id="i-post"
                                            class="custom-control-input"
                                            type="radio"
                                            v-model="type"
                                            value="1"
                                            required
                                        />
                                        <label class="custom-control-label" for="i-post">Postingan</label>
                                    </div>
                                    <div class="custom-control custom-radio mx-4">
                                        <input
                                            id="i-art"
                                            class="custom-control-input"
                                            type="radio"
                                            v-model="type"
                                            value="2"
                                            required
                                        />
                                        <label class="custom-control-label" for="i-art">Artikel</label>
                                    </div>
                                    <div class="custom-control custom-radio">
                                        <input
                                            id="i-work"
                                            class="custom-control-input"
                                            type="radio"
                                            v-model="type"
                                            value="3"
                                            required
                                        />
                                        <label
                                            class="custom-control-label"
                                            for="i-work"
                                        >Rekomendasi Perusahaan</label>
                                    </div>
                                </div>
                                <input
                                    class="form-control mb-2"
                                    type="text"
                                    v-model="title"
                                    :placeholder="type == 3 ? 'Nama Perusahaan' : 'Judul'"
                                    required
                                />
                                <input
                                    v-if="type == 3"
                                    class="form-control mb-2"
                                    type="text"
                                    v-model="location"
                                    placeholder="Lokasi"
                                    required
                                />
                                <input-mark v-model="desc" :configs="config" />
                                <button
                                    :disabled="clicked === true || type === null"
                                    class="btn btn-primary"
                                >Kirim {{ type == 1 ? 'Postingan' : (type == 2 ? 'Artikel' : (type == 3 ? 'Rekomendasi' : '')) }}</button>
                                <fa
                                    v-if="clicked"
                                    icon="spinner"
                                    spin
                                    size="lg"
                                    class="text-primary"
                                />
                            </form>
                        </div>
                        <div v-else-if="i === 1">
                            <div v-if="desc != ''">
                                <markdown class="md-body" :content="desc" />
                            </div>
                            <div v-else>
                                <h4 class="text-muted text-center">Inputan belum diisi</h4>
                            </div>
                        </div>
                        <div v-else>
                            <guide />
                        </div>
                    </div>
                </div>
                <div v-if="post.length">
                    <div v-for="(i, key) in post" :key="key" class="card mb-3 custom-limit">
                        <div class="card-header border-0 bg-white">
                            <div class="d-none">{{ isVoted(i.id, key) }}</div>
                            <div class="row pb-0">
                                <div class="col-1 text-center p-0">
                                    <fa
                                        class="btn-vote-up"
                                        :class="i.temp == 1 ? 'text-success' : ''"
                                        icon="caret-up"
                                        size="2x"
                                        @click="vote(i.id, i.type, i.temp == 1 ? 0 : 1, key, 1)"
                                    />
                                    <p
                                        class="p-0 m-0"
                                        :class="i.temp !== undefined ? (i.temp === 1 ? 'text-success' : (i.temp === -1 ? 'text-danger' : '')) : ''"
                                        :title="i.votes"
                                    >{{ i.votes | voteFilter }}</p>
                                    <fa
                                        class="btn-vote-down"
                                        :class="i.temp == -1 ? 'text-danger' : ''"
                                        icon="caret-down"
                                        size="2x"
                                        @click="vote(i.id, i.type, i.temp == -1 ? 0 : -1, key, 0)"
                                    />
                                </div>
                                <div class="col-11">
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
                        <div class="card-body pt-0 custom-hover" @click="detail(i.id)">
                            <h5 v-if="i.type == 3" class="text-muted">{{ i.location }}</h5>
                            <markdown class="mb-body" :content="i.desc" />
                            <div class="custom-bottom"></div>
                        </div>
                        <div class="card-footer border-0 custom-bg-none text-right">
                            <small class="text-muted">
                                <fa icon="comment-dots" />
                                {{ ' ' + i.total }}
                            </small>
                        </div>
                    </div>
                </div>
                <div v-else class="card mb-3">
                    <div class="card-header border-0 bg-white">
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
                                        <div class="pt-2 mt-2 bg-lighten rounded-pill col-3 ml-1"></div>
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
                    <div class="card-body pt-0">
                        <div
                            v-for="(i, key) in 3"
                            :key="key"
                            class="pt-4 mb-2 bg-lighten rounded-pill"
                        ></div>
                        <div class="pt-4 mt-2 bg-lighten rounded-pill col-6"></div>
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
                </infinite>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="sticky-top pt-3">
                    <div class="card">
                        <div class="card-header text-center">
                            Rekomendasi Perusahaan
                            <span
                                class="btn float-right p-0 m-0"
                                @click="sideRand(3, 3)"
                            >
                                <fa icon="redo" :spin="workClicked === true" />
                            </span>
                        </div>
                        <ul v-if="work.length" class="list-group list-group-flush">
                            <li
                                v-for="(i, key) in work"
                                :key="key"
                                class="list-group-item custom-hover"
                                @click="detail(i.id)"
                            >{{ i.title | titleCase | limitTitle(30) }}</li>
                        </ul>
                        <ul v-else class="list-group list-group-flush">
                            <li v-for="(i, key) in 3" :key="key" class="list-group-item">
                                <div
                                    class="pt-4 bg-lighten rounded-pill"
                                    :class="'col-'+(Math.floor(Math.random() * 12))"
                                ></div>
                            </li>
                        </ul>
                    </div>
                    <div class="card my-3">
                        <div class="card-header text-center">
                            Artikel
                            <span class="btn float-right p-0 m-0" @click="sideRand(2, 5)">
                                <fa icon="redo" :spin="artClicked === true" />
                            </span>
                        </div>
                        <ul v-if="art.length" class="list-group list-group-flush">
                            <li
                                v-for="(i, key) in art"
                                :key="key"
                                class="list-group-item custom-hover"
                                @click="detail(i.id)"
                            >{{ i.title | titleCase | limitTitle }}</li>
                        </ul>
                        <ul v-else class="list-group list-group-flush">
                            <li v-for="(i, key) in 5" :key="key" class="list-group-item">
                                <div
                                    class="pt-4 bg-lighten rounded-pill"
                                    :class="'col-'+(Math.floor(Math.random() * 12))"
                                ></div>
                            </li>
                        </ul>
                    </div>
                    <div class="card text-center">
                        <div class="card-body py-2">&copy; Forum RPL</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        config: {
            toolbar: [
                "bold",
                "italic",
                "heading",
                "|",
                "code",
                "unordered-list",
                "ordered-list",
                "|",
                "link",
                "image",
                "|",
                "strikethrough",
                "table"
            ],
            placeholder: "Ketik disini..",
            indentWithTabs: false,
            spellChecker: false,
            tabSize: 4
        },
        desc: "",
        title: "",
        location: "",
        i: 0,
        type: null,
        clicked: false,
        post: [],
        skip: 0,
        work: [],
        workClicked: true,
        art: [],
        artClicked: true,
        err: [],
        voted: [],
        canLeave: false,
        once: true,
        secret: false
    }),
    mounted() {
        if (this.$auth.check() && this.$auth.user().role === 1)
            this.$router.push({ name: "home-admin" });
        else
            axios
                .post(`/user/home/${this.$auth.user().id}`)
                .then(resp => {
                    const r = resp.data;

                    this.art = r.art;
                    this.artClicked = false;
                    this.work = r.work;
                    this.workClicked = false;
                    this.voted = r.voted;
                })
                .catch(err => console.error(err.response));
    },
    methods: {
        page(i) {
            this.i = i;
        },
        submited() {
            if (this.desc != "") {
                this.clicked = true;
                axios
                    .post(`/user/store/${this.$auth.user().id}`, {
                        type: this.type,
                        title: this.title,
                        location: this.location,
                        desc: this.desc
                    })
                    .then(resp => {
                        this.clicked = false;
                        this.canLeave = true;
                        this.$router.push(`/home/detail/${resp.data.id}`);
                    })
                    .catch(err => {
                        console.error(err.response);
                        this.err = err.response.data;
                        this.clicked = false;
                    });
            }
        },
        sideRand(type, take) {
            this.clickCheck(type, true);
            axios
                .post(`/user/home/side/${type}/${take}`)
                .then(resp => {
                    if (type === 2) this.art = resp.data;
                    else this.work = resp.data;

                    this.clickCheck(type, false);
                })
                .catch(err => {
                    console.error(err.response);
                    this.clickCheck(type, false);
                });
        },
        clickCheck(type, bool) {
            if (type === 2) this.artClicked = bool;
            else this.workClicked = bool;
        },
        detail(id) {
            this.$router.push({ path: `/home/detail/${id}` });
        },
        vote(id, type, v, key, btn) {
            if (this.$auth.check()) {
                this.post[key].temp = v;

                axios
                    .post(
                        `/user/vote/${id}/${type}/${this.$auth.user().id || 0}`,
                        {
                            vote: v,
                            parent: id
                        }
                    )
                    .then(resp => {
                        this.voted = resp.data;
                        v === 0
                            ? btn === 1
                                ? (this.post[key].votes -= 1)
                                : (this.post[key].votes += 1)
                            : (this.post[key].votes += v);
                    })
                    .catch(err => console.error(err.response));
            }
        },
        isVoted(id, key) {
            if (this.$auth.check() && this.post.length) {
                const v = this.voted.filter(
                    e =>
                        e.type_id === id &&
                        e.vote !== 0 &&
                        e.user_id === this.$auth.user().id
                );

                this.once && v.length
                    ? Vue.set(this.post[key], "temp", v[0].vote)
                    : "";
                key === this.post.length - 1 ? (this.once = false) : "";
            }
            return;
        },
        scrollLoad($state) {
            axios
                .post(`/user/home/${this.skip}/${this.$auth.user().id}`)
                .then(resp => {
                    if (resp.data.length) {
                        this.post.push(...resp.data);
                        this.skip += 10;
                        this.once = true;
                        try {
                            $state.loaded();
                        } catch (e) {}
                    } else {
                        try {
                            $state.complete();
                        } catch (e) {}
                    }
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
        limitTitle(str, n = 40) {
            return str.length > n ? str.substring(0, n) + "..." : str;
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
    beforeRouteLeave(to, from, next) {
        if (
            this.$auth.check() &&
            this.canLeave === false &&
            (this.desc != "" || this.title != "" || this.type != null)
        ) {
            if (
                window.confirm(
                    "Inputan yang kamu isi belum dikirim!\nApakah kamu yakin mau keluar dari halaman ini?"
                )
            )
                next();
            else next(false);
        } else next();
    }
};
</script>
