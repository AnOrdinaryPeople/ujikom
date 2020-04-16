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
            { title: "/api/comment/{post_id}", left: "", right: "" },
            { title: "/api/comment/{post_id}/rand", left: "", right: "" },
            {
                title: "/api/comment/{post_id}/paginate/{per_page}",
                left: "",
                right: ""
            }
        ]
    }),
    created() {
        this.txt[0].left = `URL comment:

\`${this.sauce}/api/comment/{post_id}\` \`${this.sauce}/api/comment/{post_id}/rand\` \`${this.sauce}/api/comment/{post_id}/paginate/{per_page}\``;
        this.txt[1].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
limit | int | 10 | Membatasi jumlah data yang diterima
skip | int | 0 | Melewati beberapa data yang ditentukan
sort_by | string | id | Mengurutkan data yang ditentukan
sort | string | desc | Mengurutkan data dari atas atau bawah
user_id | int | *null* | Menampilkan data dari user yang ditentukan
Contoh penggunaan:

\`/api/comment/${Math.floor(
            Math.random() * 100
        )}?limit=20\` Jumlah data menjadi 20

\`/api/comment/${Math.floor(
            Math.random() * 100
        )}?limit=10&skip=5\` Jumlah data menjadi 10 dan data yang dilewati 5

\`/api/comment/${Math.floor(
            Math.random() * 100
        )}?sort=asc\` Menampilkan data dari id terkecil sampai terbesar

\`/api/comment/${Math.floor(
            Math.random() * 100
        )}?sort_by=votes&sort=desc\` Menampilkan vote terbesar sampai terkecil`;
        this.txt[1].right = `Data yang seharusnya diterima
\`\`\`js
{
    "comment": [
        {
            "id": 529,
            "user_id": 23,
            "desc": "Dolorem iste voluptates consequatur autem id ex. Culpa quos aspernatur exercitationem non ut. Est autem aut ipsam ut voluptatum at sit. Dolore odit non nostrum rerum esse delectus vitae distinctio. Asperiores aut culpa eveniet exercitationem nam ut eligendi. Excepturi qui fugit autem et qui ea omnis placeat. In consequatur praesentium officiis iure aut quam. Fugit a magni hic enim quibusdam veniam. Enim tempora officiis consectetur id dolorem. Laboriosam rerum tenetur voluptatem recusandae explicabo pariatur sit.",
            "votes": 42,
            "created_at": "2020-04-16 08:42:17"
        }, {
            "id": 528,
            "user_id": 22,
            "desc": "Ut animi accusamus libero. Laboriosam voluptatem autem molestias deserunt harum sequi. Et et quae nihil optio ut consectetur soluta. Eos voluptatem voluptatem dolor consequuntur explicabo officiis eos. Officiis atque qui atque quia minima. Quidem velit sequi deserunt atque rem laboriosam dolores. Vel laboriosam assumenda rerum reiciendis.",
            "votes": 94,
            "created_at": "2020-04-16 08:42:17"
        }, {
            "id": 527,
            "user_id": 6,
            "desc": "Et dolores voluptas neque beatae. Assumenda eos aut perspiciatis. Animi odio dolores dolor sapiente. Dolorem necessitatibus hic iste. Eligendi odit non magnam.",
            "votes": 44,
            "created_at": "2020-04-16 08:42:17"
        }, {
            "id": 526,
            "user_id": 11,
            "desc": "Recusandae quis molestiae dolor officia illum fugit atque maxime. Quis rerum modi at. Fugiat aliquam nulla culpa amet consequuntur unde. Odit cumque repudiandae quo voluptatibus. Ex aliquam optio nisi possimus consequatur aut. Eligendi aperiam temporibus et ratione quia. Et reiciendis quas aliquid quis esse. Maiores et inventore enim nobis enim.",
            "votes": 10,
            "created_at": "2020-04-16 08:42:17"
        }, {
            "id": 57,
            "user_id": 25,
            "desc": "Non magnam nobis ut expedita esse molestiae earum. Ut ipsum odio officiis aliquid eaque enim. Quia aspernatur numquam et soluta voluptates aperiam. Et possimus deleniti asperiores dolores eos aut eveniet. Ut vitae omnis quia est sed at quia. Dignissimos praesentium nesciunt facilis maxime. Dolorem modi reiciendis dolores qui totam dolorem. Vel occaecati sequi facilis similique ab et eaque. Enim qui provident voluptas excepturi similique. Aut eaque dolores amet modi. Est a facere enim accusamus voluptatum impedit perspiciatis. Vitae dolorum cupiditate suscipit soluta. Ea vel incidunt dicta non et earum. Ex facere earum harum et sed. Tempore sed ex minus commodi sapiente rerum id quis. Cupiditate voluptate blanditiis hic doloremque id.",
            "votes": 966,
            "created_at": "2020-04-16 08:42:16"
        }
    ]
}
\`\`\``;
        this.txt[2].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
limit | int | 10 | Membatasi jumlah data yang diterima
skip | int | 0 | Melewati beberapa data yang ditentukan
Contoh penggunaan:

\`/api/comment/${Math.floor(
            Math.random() * 100
        )}/rand?limit=20\` Jumlah data menjadi 20

\`/api/comment/${Math.floor(
            Math.random() * 100
        )}/rand?limit=10&skip=5\` Jumlah data menjadi 10 dan data yang dilewati 5`;
        this.txt[2].right = `Data yang seharusnya diterima
\`\`\`js
{
    "comment": [
        {
            "id": 52,
            "user_id": 27,
            "desc": "Eaque voluptas alias molestiae et exercitationem odio illo vel. Est mollitia quasi ducimus impedit quia suscipit illo eos. Cum accusamus explicabo iste repudiandae facilis enim. Quo dignissimos officia dignissimos asperiores enim consequatur illum vel. Impedit et natus quisquam nisi illum. Sapiente alias esse sed. Cum dolorum officiis minus aut similique pariatur facilis. Quas maxime nihil perspiciatis iure eligendi consequuntur. Id in dolore et perferendis ab rerum. Doloremque sint provident quia nisi. Et non minus odit incidunt. Debitis est natus quo dolorum id recusandae iusto. Et velit error minima.",
            "votes": 16,
            "created_at": "2020-04-16 08:42:16"
        }, {
            "id": 528,
            "user_id": 22,
            "desc": "Ut animi accusamus libero. Laboriosam voluptatem autem molestias deserunt harum sequi. Et et quae nihil optio ut consectetur soluta. Eos voluptatem voluptatem dolor consequuntur explicabo officiis eos. Officiis atque qui atque quia minima. Quidem velit sequi deserunt atque rem laboriosam dolores. Vel laboriosam assumenda rerum reiciendis.",
            "votes": 94,
            "created_at": "2020-04-16 08:42:17"
        }, {
            "id": 54,
            "user_id": 27,
            "desc": "Magni cupiditate fugit molestiae tenetur dignissimos minima. Enim dicta exercitationem dolorem recusandae qui. Et voluptatem et eligendi ut architecto a. Ipsa facilis cumque ea incidunt nisi iure. Illo quia et ut perspiciatis porro aliquid ut. Quidem deleniti pariatur quod dicta omnis impedit. Blanditiis recusandae impedit dolores iusto libero quo. Quis rerum inventore deserunt voluptas autem nisi omnis. Est nihil sequi enim iusto sint nihil itaque. Et reprehenderit aut totam molestias veniam neque qui. Id alias eligendi maxime omnis. Quas consequuntur saepe fugit ducimus unde quos. Quasi est pariatur iste consequatur. Blanditiis qui reiciendis mollitia molestiae necessitatibus. Est qui et laborum unde.",
            "votes": 990,
            "created_at": "2020-04-16 08:42:16"
        }, {
            "id": 55,
            "user_id": 16,
            "desc": "Aut asperiores ab quia delectus accusantium incidunt nisi. Quam id dolor eum dicta omnis. Impedit veniam impedit magnam iste nam. Quisquam accusamus quo consequatur eum sapiente ipsam et. Vel quas possimus quod minima doloribus. Ut incidunt aliquid quia autem vero. Ullam nulla reprehenderit tempora. Quasi dolor aut sit fugiat placeat. Voluptas rerum eos dolor veniam vel dolores. Quam occaecati eligendi excepturi autem sit architecto. Voluptas sunt architecto nihil nihil. Voluptatum nesciunt quia nobis reiciendis quis. Ab velit unde soluta. Quidem suscipit assumenda alias doloremque. Sed nihil culpa sunt quam natus sunt.",
            "votes": 880,
            "created_at": "2020-04-16 08:42:16"
        }, {
            "id": 49,
            "user_id": 22,
            "desc": "Maxime quasi natus magni ducimus. Deserunt consequatur placeat ut enim nesciunt. Laboriosam quaerat amet eaque eos eum voluptas. Ipsam quae accusamus consectetur qui delectus quasi molestiae minus. Voluptatum sapiente aut optio sed. Architecto rerum suscipit laborum iure. Autem ab sint ea. Deserunt similique illo accusamus illo et doloremque. Molestiae eaque corrupti illo voluptate aspernatur. Similique ullam et laudantium et. Consequatur natus inventore sit eius.",
            "votes": 167,
            "created_at": "2020-04-16 08:42:16"
        }
    ]
}
\`\`\``;
        this.txt[3].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
sort_by | string | id | Mengurutkan data yang ditentukan
sort | string | desc | Mengurutkan data dari atas atau bawah
Contoh penggunaan:

\`/api/comment/${Math.floor(
            Math.random() * 100
        )}/paginate?sort=asc\` Menampilkan data dari id terkecil sampai terbesar

\`/api/comment/${Math.floor(Math.random() * 100)}/paginate/${Math.floor(
            Math.random() * 21
        )}?sort_by=name&sort=desc\` Menampilkan data dari nama Z - A

**Untuk mengubah halaman:**

\`/api/comment/${Math.floor(Math.random() * 100)}/paginate?page=2\`

\`/api/comment/${Math.floor(Math.random() * 100)}/paginate/${Math.floor(
            Math.random() * 21
        )}?page=2\`

\`/api/comment/${Math.floor(Math.random() * 100)}/paginate/${Math.floor(
            Math.random() * 21
        )}?page=2&sort_by=name&sort=desc\``;
        this.txt[3].right = `Data yang seharusnya diterima
\`\`\`js
{
    "current_page": 1,
    "data": [
        {
            "id": 44,
            "user_id": 22,
            "desc": "Debitis voluptatibus omnis voluptatem nulla voluptatem. Nobis porro provident autem cum nesciunt aliquam. Repudiandae quo accusamus veritatis quia et. Vero ullam est ea sunt et molestias nihil. Blanditiis sunt officiis atque dolore. Fugit possimus officiis molestiae ut. Qui neque enim ipsa aut amet et iste iusto. Officiis qui ut eos dignissimos. Ut mollitia illum similique aliquid deleniti ratione. Dignissimos dolor qui nam. Ut asperiores sunt eaque voluptas vel fugit deserunt. Et nulla natus debitis impedit vel. Culpa quia aperiam accusamus sed. Quaerat esse repellat quis repellat excepturi adipisci. Quas quisquam ullam delectus aliquam incidunt. Sapiente laudantium assumenda fugit atque. In doloremque quod atque voluptas corrupti. Sit neque repellat eius quod. Labore ab dolor sed et.",
            "votes": 492,
            "created_at": "2020-04-16 08:42:16"
        },
        {
            "id": 43,
            "user_id": 8,
            "desc": "Cum eius sed numquam ut eveniet rem perferendis. Corporis eaque ad quia a quidem dicta maiores dolor. Quia eligendi nisi voluptatem distinctio incidunt aut. Voluptatem quidem ab voluptatum dolorem reiciendis libero veritatis. Et animi officiis similique neque placeat eius. Exercitationem vel culpa et repellendus itaque impedit delectus quia. Amet et quos et est. Nihil similique fuga molestiae totam. Placeat temporibus nihil illum inventore autem blanditiis voluptates officia. Voluptatem et voluptatem voluptates aspernatur maiores non ab.",
            "votes": 127,
            "created_at": "2020-04-16 08:42:16"
        },
        {
            "id": 42,
            "user_id": 18,
            "desc": "Ea esse expedita omnis sed voluptatem est molestias tempora. Dolores consequatur sit ipsum enim. Sint quia beatae molestiae fugiat rerum officia. Voluptatem dolor dignissimos earum eveniet quam. Aut voluptas officiis quae cumque dolorem voluptatibus voluptates dignissimos. Quibusdam quis pariatur pariatur qui praesentium. Veritatis dolor mollitia quia. Vero sunt velit et esse qui ut. Delectus ut iusto a sint maxime incidunt. Impedit porro nostrum voluptatem eius culpa sed perspiciatis. Non et magnam labore fugit ut. Accusantium esse iure voluptas voluptatem.",
            "votes": 121,
            "created_at": "2020-04-16 08:42:16"
        },
        {
            "id": 41,
            "user_id": 19,
            "desc": "Est sit quaerat consectetur velit incidunt minus. Cum voluptas repellat pariatur odit. Officia dolor earum alias et impedit qui est eveniet. Vero rerum illo enim qui explicabo officiis magnam est. Cum sint et et nisi ut. Impedit nihil labore ut earum. Voluptates corrupti et voluptatum eaque quo et nihil et. Non suscipit enim ut exercitationem. Sunt harum nesciunt laboriosam voluptatem. Dolor sit sed et optio. Qui iure excepturi totam quam sapiente.",
            "votes": 161,
            "created_at": "2020-04-16 08:42:16"
        },
        {
            "id": 40,
            "user_id": 26,
            "desc": "Dolore blanditiis itaque omnis mollitia velit. Optio sunt id voluptatibus culpa placeat et. Debitis in dolorem officia et vel. Eum veritatis autem sit et libero rerum praesentium. Veniam sed eius quia nemo vel. Distinctio porro excepturi dolorem quam. Corporis praesentium laudantium similique consectetur accusantium quo quisquam. Assumenda veniam earum pariatur molestiae delectus aut laborum. Dolores nobis enim aut aut. Necessitatibus qui adipisci mollitia eveniet illo voluptatem laudantium.",
            "votes": 711,
            "created_at": "2020-04-16 08:42:16"
        }
    ],
    "first_page_url": "${this.sauce}/api/comment/4/paginate/5?page=1",
    "from": 1,
    "last_page": 2,
    "last_page_url": "${this.sauce}/api/comment/4/paginate/5?page=2",
    "next_page_url": "${this.sauce}/api/comment/4/paginate/5?page=2",
    "path": "${this.sauce}/api/comment/4/paginate/5",
    "per_page": "5",
    "prev_page_url": null,
    "to": 5,
    "total": 8
}
\`\`\``;
    }
};
</script>
