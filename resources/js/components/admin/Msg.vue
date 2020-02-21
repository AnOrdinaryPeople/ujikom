<template>
    <div class="container-fluid">
        <div v-if="loaded" class="row">
            <div class="col-lg-9 col-md-9 col-sm-12">
                <input
                    v-if="data.data.length"
                    class="form-control col-lg-6 col-md-12 col-sm-12 mx-auto my-3"
                    type="text"
                    v-model="q"
                    placeholder="Cari nama.."
                />
                <table v-if="data.data.length" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Pesan untuk</th>
                            <th>Deskripsi</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(i, key) in list" :key="key">
                            <td>{{ i.name }}</td>
                            <td>{{ i.desc | limit }}</td>
                            <td>
                                <button
                                    class="btn btn-info"
                                    title="Detail"
                                    @click="open(key, 'msg-detail')"
                                >
                                    <fa icon="eye" />
                                </button>
                                <button
                                    class="btn btn-primary"
                                    title="Edit"
                                    @click="open(key, 'msg-edit')"
                                >
                                    <fa icon="pen" />
                                </button>
                                <button
                                    class="btn btn-danger"
                                    title="Hapus"
                                    @click="open(key, 'msg-delete')"
                                >
                                    <fa icon="trash" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table v-else class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Tidak ada pesan</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="position-stiky pt-3">
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
        <admin-modal idd="msg-detail" title="Detail" icon="reply-all" size="modal-lg">
            <div slot="body">
                <markdown v-if="d.desc !== undefined" class="mb-body" :content="d.desc" />
            </div>
        </admin-modal>
        <admin-modal idd="msg-edit" title="Edit" icon="pen" size="modal-lg" :is-form="true">
            <div slot="body">
                <h5>Edit pesan {{ d.name }}?</h5>
                <div class="form-group">
                    <admin-invut @send="updateMsg" ref="invut" />
                </div>
            </div>
        </admin-modal>
        <admin-modal
            idd="msg-delete"
            title="Konfirmasi Hapus"
            icon="exclamation-triangle"
            bg-title="bg-danger text-light"
            :is-form="true"
            @send="del"
        >
            <div slot="body">
                <p>Apakah anda yakin hapus pesan ini?</p>
            </div>
            <div slot="footer">
                <button class="btn btn-danger" :disabled="clicked">Kirim</button>
                <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
            </div>
        </admin-modal>
    </div>
</template>

<script>
export default {
    data: () => ({
        loaded: false,
        data: { data: [] },
        q: "",
        sort: [
            { name: "Terbaru", icon: "sort-amount-up" },
            { name: "Terakhir", icon: "sort-amount-down" },
            { name: "Nama A-Z", icon: "sort-alpha-up" },
            { name: "Nama Z-A", icon: "sort-alpha-down" }
        ],
        sortKey: 0,
        d: {},
        clicked: false
    }),
    mounted() {
        this.getData();
    },
    methods: {
        changePage(page = 1) {
            axios.post("admin/msg?page=" + page).then(resp => {
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
        getData() {
            var p = this.$route.query.page;

            axios
                .post(`admin/msg${p !== undefined ? "?page=" + p : ""}`)
                .then(resp => {
                    this.data = resp.data;
                    this.loaded = true;
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

                default:
                    break;
            }
        },
        open(key, id) {
            this.d = this.data.data[key];
            $("#" + id).modal();
            if (id === "msg-edit") this.$refs.invut.set(this.d.desc);
        },
        updateMsg(data) {
            axios
                .post(`admin/msg/update/${this.d.id}`, { desc: data })
                .then(() => {
                    this.$refs.invut.reset();
                    $("#msg-edit").modal("hide");
                    this.getData();
                });
        },
        del() {
            this.clicked = true;
            axios
                .post(`admin/msg/destroy/${this.d.id}/${this.d.user_id}`)
                .then(() => {
                    $("#msg-delete").modal("hide");
                    this.clicked = false;
                    this.getData();
                });
        }
    },
    computed: {
        list() {
            return this.q !== ""
                ? this.data.data.filter(
                      e =>
                          e.name.toLowerCase().indexOf(this.q.toLowerCase()) >
                          -1
                  )
                : this.data.data;
        }
    },
    filters: {
        limit(str) {
            return str.length > 50 ? str.substring(0, 50) + "..." : str;
        }
    }
};
</script>
