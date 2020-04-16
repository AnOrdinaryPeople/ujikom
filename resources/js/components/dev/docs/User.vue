<template>
    <div class="container">
        <docs-component :data="txt" />
    </div>
</template>

<script>
export default {
    data: () => ({
        sauce: process.env.MIX_APP_URL,
        txt: [
            { title: "", left: "", right: "" },
            { title: "/api/user", left: "", right: "" },
            { title: "/api/user/rand", left: "", right: "" },
            { title: "/api/user/paginate/{per_page}", left: "", right: "" }
        ]
    }),
    created() {
        this.txt[0].left = `URL user:

\`${this.sauce}/api/user\` \`${this.sauce}/api/user/rand\` \`${this.sauce}/api/user/paginate/{per_page}\``;
        this.txt[1].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
limit | int | 10 | Membatasi jumlah data yang diterima
skip | int | 0 | Melewati beberapa data yang ditentukan
sort_by | string | id | Mengurutkan data yang ditentukan
sort | string | desc | Mengurutkan data dari atas atau bawah
Contoh penggunaan:

\`/api/user?limit=20\` Jumlah data menjadi 20

\`/api/user?limit=10&skip=5\` Jumlah data menjadi 10 dan data yang dilewati 5

\`/api/user?sort=asc\` Menampilkan data dari id terkecil sampai terbesar

\`/api/user?sort_by=name&sort=desc\` Menampilkan data dari nama Z - A`;
        this.txt[1].right = `Data yang seharusnya diterima
\`\`\`js
[
    {
        "id": 27,
        "name": "Stacey Brekke",
        "email": "user25@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-03-29 12:01:45"
    }, {
        "id": 26,
        "name": "Prof. Laurel Leannon",
        "email": "user24@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-03-29 12:01:45"
    }, {
        "id": 25,
        "name": "Brendan Little",
        "email": "user23@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-11 12:01:44"
    }, {
        "id": 24,
        "name": "Zoie O'Reilly",
        "email": "user22@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-03-24 12:01:44"
    }, {
        "id": 23,
        "name": "Mr. Jamil Batz DDS",
        "email": "user21@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-07 12:01:44"
    }, {
        "id": 22,
        "name": "Litzy Trantow",
        "email": "user20@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-01 12:01:44"
    }, {
        "id": 21,
        "name": "Dr. Reymundo Bashirian Sr.",
        "email": "user19@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-03-28 12:01:44"
    }, {
        "id": 20,
        "name": "Adelbert Abernathy",
        "email": "user18@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-03-23 12:01:44"
    }, {
        "id": 19,
        "name": "Joseph Champlin",
        "email": "user17@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-03 12:01:44"
    }, {
        "id": 18,
        "name": "Terrance Feil",
        "email": "user16@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-05 12:01:44"
    }
]
\`\`\``;
        this.txt[2].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
limit | int | 10 | Membatasi jumlah data yang diterima
skip | int | 0 | Melewati beberapa data yang ditentukan
Contoh penggunaan:

\`/api/user/rand?limit=20\` Jumlah data menjadi 20

\`/api/user/rand?limit=10&skip=5\` Jumlah data menjadi 10 dan data yang dilewati 5`;
        this.txt[2].right = `Data yang seharusnya diterima
\`\`\`js
[
    {
        "id": 16,
        "name": "Dr. Zetta DuBuque",
        "email": "user14@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-03-31 08:42:15"
    }, {
        "id": 15,
        "name": "Celia Nicolas",
        "email": "user13@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-03 08:42:15"
    }, {
        "id": 20,
        "name": "Liliana O'Connell",
        "email": "user18@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-09 08:42:16"
    }, {
        "id": 25,
        "name": "Maryjane Kassulke",
        "email": "user23@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-14 08:42:16"
    }, {
        "id": 12,
        "name": "Maia Wehner III",
        "email": "user10@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-14 08:42:15"
    }, {
        "id": 8,
        "name": "Esperanza Lebsack",
        "email": "user6@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-15 08:42:15"
    }, {
        "id": 3,
        "name": "Alvera Bauch DVM",
        "email": "user1@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-03-24 08:42:14"
    }, {
        "id": 13,
        "name": "Cielo Dare",
        "email": "user11@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-08 08:42:15"
    }, {
        "id": 22,
        "name": "Dr. Viola Pouros",
        "email": "user20@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-13 08:42:16"
    }, {
        "id": 23,
        "name": "Ms. Earlene Stehr",
        "email": "user21@mail.com",
        "email_verified_at": null,
        "avatar": "${this.sauce}/img/user.png",
        "created_at": "2020-04-09 08:42:16"
    }
]
\`\`\``;
        this.txt[3].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
sort_by | string | id | Mengurutkan data yang ditentukan
sort | string | desc | Mengurutkan data dari atas atau bawah
Contoh penggunaan:

\`/api/user/paginate?sort=asc\` Menampilkan data dari id terkecil sampai terbesar

\`/api/user/paginate/${Math.floor(
            Math.random() * 21
        )}?sort_by=name&sort=desc\` Menampilkan data dari nama Z - A

**Untuk mengubah halaman:**

\`/api/user/paginate?page=2\`

\`/api/user/paginate/${Math.floor(Math.random() * 21)}?page=2\`

\`/api/user/paginate/${Math.floor(
            Math.random() * 21
        )}?page=2&sort_by=name&sort=desc\``;
        this.txt[3].right = `Data yang seharusnya diterima
\`\`\`js
{
    "current_page": 1,
    "data": [
        {
            "id": 27,
            "name": "Ms. Ollie Braun I",
            "email": "user25@mail.com",
            "email_verified_at": null,
            "avatar": "${this.sauce}/img/user.png",
            "created_at": "2020-04-09 08:42:16"
        }, {
            "id": 26,
            "name": "Prof. Kristy Murray PhD",
            "email": "user24@mail.com",
            "email_verified_at": null,
            "avatar": "${this.sauce}/img/user.png",
            "created_at": "2020-04-13 08:42:16"
        }, {
            "id": 25,
            "name": "Maryjane Kassulke",
            "email": "user23@mail.com",
            "email_verified_at": null,
            "avatar": "${this.sauce}/img/user.png",
            "created_at": "2020-04-14 08:42:16"
        }, {
            "id": 24,
            "name": "Dr. Harry Roob MD",
            "email": "user22@mail.com",
            "email_verified_at": null,
            "avatar": "${this.sauce}/img/user.png",
            "created_at": "2020-03-22 08:42:16"
        }, {
            "id": 23,
            "name": "Ms. Earlene Stehr",
            "email": "user21@mail.com",
            "email_verified_at": null,
            "avatar": "${this.sauce}/img/user.png",
            "created_at": "2020-04-09 08:42:16"
        }
    ],
    "first_page_url": "${this.sauce}/api/user/paginate/5?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "${this.sauce}/api/user/paginate/5?page=2",
    "next_page_url": "${this.sauce}/api/user/paginate/5?page=2",
    "path": "${this.sauce}/api/user/paginate/15",
    "per_page": "5",
    "prev_page_url": null,
    "to": 5,
    "total": 26
}
\`\`\``;
    }
};
</script>
