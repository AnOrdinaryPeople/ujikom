<template>
    <div class="container">
        <div v-if="loaded" class="row">
            <div class="col-lg-9 col-md-9 col-sm-12">
                <table v-if="data.data.length" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nama User</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Riwayat</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(i, key) in list" :key="key">
                            <td>{{ i.name }}</td>
                            <td>{{ i.desc | limit }}</td>
                            <td>{{ i.created_at | dt }}</td>
                            <td>
                                <button class="btn btn-info" @click="open(key)">
                                    <fa icon="eye" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table v-else class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Tidak ada riwayat</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="position-sticky pt-3">
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
        <admin-modal idd="h-detail" title="Detail" icon="info">
            <div slot="body">
                <p>{{ desc }}</p>
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
            { name: "Terakhir", icon: "sort-amount-down" }
        ],
        sortKey: 0,
        desc: ""
    }),
    mounted() {
        var p = this.$route.query.page;

        axios
            .post(`admin/history${p !== undefined ? "?page=" + p : ""}`)
            .then(resp => {
                this.data = resp.data;
                this.loaded = true;
            });
    },
    methods: {
        changePage(page = 1) {
            axios.post("admin/history?page=" + page).then(resp => {
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

                default:
                    break;
            }
        },
        open(key) {
            this.desc = this.data.data[key].desc;
            $("#h-detail").modal();
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
        },
        dt(str) {
            return new Date(str).toLocaleString("id-ID", {
                dateStyle: "long"
            });
        }
    }
};
</script>
