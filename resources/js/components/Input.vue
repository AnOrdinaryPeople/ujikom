<template>
    <div>
        <div v-if="pg == 'editReply' || pg == 'reply' || (pg == 'edit' && load)" class="card">
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
                        <div v-if="pg === 'post' || pg === 'edit'" class="form-inline mb-2">
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
                            v-if="pg === 'edit'"
                            class="form-control mb-2"
                            type="text"
                            v-model="title"
                            :placeholder="type == 3 ? 'Nama Perusahaan' : 'Judul'"
                            required
                        />
                        <input
                            v-if="pg === 'edit' && type === 3"
                            class="form-control mb-2"
                            type="text"
                            v-model="location"
                            placeholder="Lokasi"
                            required
                        />
                        <input-mark v-model="desc" :configs="config" ref="inputComp" />
                        <button
                            :disabled="clicked === true"
                            class="btn btn-primary"
                        >{{pg == 'edit' ? 'Update Postingan' : 'Kirim Komentar'}}</button>
                        <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
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
        <div v-else class="card">
            <div class="card-header custom-bg-none">
                <ul class="nav nav-tabs card-header-tabs row">
                    <li v-for="(i, key) in 3" :key="key" class="nav-item col-4">
                        <div class="nav-link bg-lighten pt-4"></div>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="row">
                    <div v-for="(i, key) in 3" :key="key" class="col-4">
                        <div class="row">
                            <div class="col-2">
                                <fa class="text-lighten" icon="circle" size="lg" />
                            </div>
                            <div class="col-10">
                                <div class="mt-1 pt-3 bg-lighten rounded-pill"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-2 pt-3 bg-lighten rounded-pill"></div>
                <div class="my-2 pt-3 bg-lighten rounded-pill"></div>
                <div class="my-4 bg-lighten rounded custom-h-250"></div>
                <div class="pt-4 bg-lighten rounded-pill col-2"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["idd", "sauce", "pg"],
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
        i: 0,
        err: [],
        type: null,
        title: "",
        location: "",
        desc: "",
        clicked: false,
        load: false,
        iddd: 0
    }),
    mounted() {
        if (this.pg === "edit") {
            axios
                .post(
                    `user/edit/${this.$auth.user().id}/${this.$route.params.id}`
                )
                .then(resp => {
                    this.type = resp.data.type;
                    this.title = resp.data.title;
                    this.location = resp.data.location;
                    this.desc = resp.data.desc;
                    this.load = true;
                })
                .catch(err => console.error(err.response));
        }
    },
    methods: {
        submited() {
            if (this.desc != "") {
                var obj;
                this.clicked = true;

                if (this.pg === "reply") obj = { desc: this.desc };
                else
                    obj = {
                        type: this.type,
                        title: this.title,
                        location: this.location,
                        desc: this.desc
                    };

                axios
                    .post(this.sauce, obj)
                    .then(resp => {
                        this.clicked = false;
                        this.type = null;
                        this.title = "";
                        this.location = "";
                        this.desc = "";

                        switch (this.pg) {
                            case "edit":
                                this.$router.push({
                                    path: `/home/detail/${this.$route.params.id}`
                                });
                                break;
                            case "editReply":
                            case "reply":
                                this.$emit("reply", {
                                    id: this.iddd || this.idd,
                                    paginate: this.$route.query.page || 1
                                });
                                break;

                            default:
                                break;
                        }
                    })
                    .catch(err => {
                        this.err = err.response.data;
                        console.error(err.response);
                    });
            }
        },
        page(n) {
            this.i = n;
        },
        setData(data, id = 0) {
            this.iddd = id;
            this.desc = "";
            setTimeout(() => {
                this.desc = data.desc;
            }, 200);
        }
    }
};
</script>
