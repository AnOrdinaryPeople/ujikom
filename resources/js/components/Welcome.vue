<template>
    <div>
        <div class="container-fluid bg-dark">
            <div class="row align-items-center welcome-header">
                <div class="col-6 mx-auto text-center text-light">
                    <h1>Forum RPL</h1>
                </div>
            </div>
        </div>
        <div class="container my-4">
            <div class="row text-center">
                <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <fa icon="question" size="7x" />
                            <h4 class="mt-2">Tanyakan</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12 mb-2">
                    <div class="card">
                        <div class="card-body">
                            <fa icon="tasks" size="7x" />
                            <h4 class="mt-2">Diskusikan</h4>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <fa icon="check" size="7x" />
                            <h4 class="mt-2">Selesaikan</h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row py-4 mt-4 text-light">
                <div class="col-lg-6 col-md-6 col-sm-12 mb-2">
                    <div class="card">
                        <div class="welcome-overlay">
                            <img class="card-img" :src="sauce + '/img/welcome-post.jpg'" alt />
                        </div>
                        <div class="card-img-overlay">
                            <h1>Buat Postingan</h1>
                            <p>Bingung dengan masalah kodingan yang kamu gunakan saat ini? atau ingin tahu hal hal yang belum kamu ketahui? tanyakan saja langsung pada teman teman lainnya</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card">
                        <div class="welcome-overlay">
                            <img class="card-img" :src="sauce + '/img/welcome-article.jpg'" alt />
                        </div>
                        <div class="card-img-overlay">
                            <h1>Buat Artikel</h1>
                            <p>Punya tips & trick atau guide yang teman kalian ketahui? tunggu apalagi mari bagikan ilmu itu pada teman teman yang lainnya</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row p-0">
                <div class="col-lg-6 col-md-6 col-sm-12 p-0 my-0 mx-auto">
                    <div class="card mt-2">
                        <div class="welcome-overlay">
                            <img :src="sauce + '/img/welcome-work.jpg'" class="card-img-top" />
                        </div>
                        <div class="card-img-overlay text-light">
                            <h1>Rekomendasi Perusahaan</h1>
                            <p>Masih bingung dengan perusahaan mana yang kalian akan tuju ketika sudah lulus nanti? tunggu apalagi langsung saja cek disini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-dark text-light">
            <div class="p-4 container text-center">
                <h1>Daftar Sekarang</h1>
                <router-link
                    class="btn btn-primary col-lg-1 col-md-2 col-sm-3 rounded-pill"
                    to="/register"
                >Daftar</router-link>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        sauce: process.env.MIX_APP_URL
    })
};
</script>
