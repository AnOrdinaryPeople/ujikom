<template>
    <div class="container-fluid">
        <div v-if="loaded" class="row">
            <div class="col-lg-9 col-md-9 col-sm-12">
                <input
                    class="form-control col-lg-6 col-md-12 col-sm-12 mx-auto my-3"
                    type="text"
                    v-model="q"
                    placeholder="Cari nama / judul.."
                />
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama Pembuat</th>
                            <th>Jenis</th>
                            <th>Judul</th>
                            <th>Vote</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody v-if="data.data.length">
                        <tr v-for="(i, key) in list" :key="key">
                            <td>{{ i.name }}</td>
                            <td>{{ i.type | typeFilter }}</td>
                            <td>{{ i.title }}</td>
                            <td>{{ i.votes }}</td>
                            <td>
                                <router-link
                                    class="btn btn-info"
                                    :to="`/admin/posts/${i.id}`"
                                    title="Detail"
                                >
                                    <fa icon="eye" />
                                </router-link>
                                <button class="btn btn-danger" title="Hapus" @click="open(key)">
                                    <fa icon="trash" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="sticky-top pt-3">
                    <div class="card mb-3">
                        <div class="card-header">Urutkan Tabel</div>
                        <ul class="list-group list-group-flush">
                            <li
                                v-for="(i, key) in sort"
                                :key="key"
                                class="list-group-item custom-hover"
                                :class="key === sortKey ? 'text-primary' : ''"
                                @click="orderBy(key)"
                            >
                                <fa :icon="i.icon" />
                                {{ ' ' + i.name }}
                            </li>
                        </ul>
                    </div>
                    <paginate :data="data" @pagination-change-page="changePage" />
                </div>
            </div>
        </div>
        <div v-else class="text-center">
            <fa icon="spinner" spin size="2x" class="text-primary" />
        </div>
        <admin-modal
            idd="post-del"
            title="Konfirmasi Hapus"
            size="modal-lg"
            bg-title="bg-danger text-light"
            icon="exclamation-triangle"
            :is-form="true"
        >
            <div slot="body">
                <h5>Apakah anda yakin hapus {{ d.title }}?</h5>
                <div class="form-group">
                    <label>Beri pesan ke {{ d.name }} kenapa postingan ini harus dihapus</label>
                    <admin-invut @send="send" ref="invut" />
                </div>
            </div>
        </admin-modal>
    </div>
</template>

<script>
export default {
    data: () => ({
        data: {},
        loaded: false,
        q: "",
        sortKey: 0,
        sort: [
            { name: "Terbaru", icon: "sort-amount-up" },
            { name: "Terakhir", icon: "sort-amount-down" },
            { name: "Nama A-Z", icon: "sort-alpha-up" },
            { name: "Nama Z-A", icon: "sort-alpha-down" },
            { name: "Judul A-Z", icon: "sort-alpha-up" },
            { name: "Judul Z-A", icon: "sort-alpha-down" },
            { name: "Vote 0-9", icon: "sort-numeric-up" },
            { name: "Vote 9-0", icon: "sort-numeric-down" }
        ],
        d: {}
    }),
    mounted() {
        var p = this.$route.query.page;
        axios
            .post(`admin/posts${p !== undefined ? "?page=" + p : ""}`)
            .then(resp => {
                this.data = resp.data;
                this.loaded = true;
            });
    },
    methods: {
        changePage(page = 1) {
            axios.post("admin/posts?page=" + page).then(resp => {
                this.data = resp.data;
                this.orderBy(this.sortKey);
                this.$router
                    .replace({
                        query: {
                            ...this.$route.query,
                            page: page
                        }
                    })
                    .catch(err => {});
            });
        },
        orderBy(key) {
            var d = this.data.data;
            this.sortKey = key;

            switch (key) {
                case 0:
                    return d.sort((a, b) =>
                        a.id < b.id ? 1 : b.id < a.id ? -1 : 0
                    );
                    break;
                case 1:
                    return d.sort((a, b) =>
                        a.id > b.id ? 1 : b.id > a.id ? -1 : 0
                    );
                    break;
                case 2:
                    return d.sort((a, b) =>
                        a.name > b.name ? 1 : b.name > a.name ? -1 : 0
                    );
                    break;
                case 3:
                    return d.sort((a, b) =>
                        a.name < b.name ? 1 : b.name < a.name ? -1 : 0
                    );
                    break;
                case 4:
                    return d.sort((a, b) =>
                        a.title > b.title ? 1 : b.title > a.title ? -1 : 0
                    );
                    break;
                case 5:
                    return d.sort((a, b) =>
                        a.title < b.title ? 1 : b.title < a.title ? -1 : 0
                    );
                    break;
                case 6:
                    return d.sort((a, b) =>
                        a.votes > b.votes ? 1 : b.votes > a.votes ? -1 : 0
                    );
                    break;
                case 7:
                    return d.sort((a, b) =>
                        a.votes < b.votes ? 1 : b.votes < a.votes ? -1 : 0
                    );
                    break;

                default:
                    break;
            }
        },
        open(key) {
            this.d = this.data.data[key];
            $("#post-del").modal();
        },
        send(data) {
            axios
                .post(`admin/posts/destroy/${this.d.id}/${this.d.user_id}`, {
                    desc: data
                })
                .then(() => {
                    var p = this.$route.query.page;

                    this.$refs.invut.reset();
                    $("#post-del").modal("hide");
                    axios
                        .post(
                            `admin/posts${p !== undefined ? "?page=" + p : ""}`
                        )
                        .then(resp => (this.data = resp.data));
                });
        }
    },
    computed: {
        list() {
            let filter = this.data.data;

            if (this.q !== "") {
                filter = this.data.data.filter(
                    e =>
                        e.name.toLowerCase().indexOf(this.q.toLowerCase()) >
                            -1 ||
                        e.title.toLowerCase().indexOf(this.q.toLowerCase()) > -1
                );
            }

            return filter;
        }
    },
    filters: {
        typeFilter(str) {
            return str == 1
                ? "Postingan"
                : str == 2
                ? "Artikel"
                : "Rekomendasi";
        }
    }
};
</script>
