<template>
    <v-card color="grey lighten-4" flat tile>
        <v-toolbar dense>
            <v-toolbar-title>
                <span v-show="username != null">{{ username }}さんの</span>
                Todoリスト
            </v-toolbar-title>

            <v-spacer></v-spacer>

            <v-toolbar-items>
                <v-btn icon to="/register" v-show="username == null">
                    <v-icon>person_add</v-icon>
                </v-btn>
                <v-btn icon to="/login" v-show="username == null">
                    <v-icon>login</v-icon>
                </v-btn>
                <v-btn icon @click="logout" v-show="username != null">
                    <v-icon>logout</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>
    </v-card>
</template>

<script>
export default {
    computed: {
        username() {
            const user = this.$store.getters["auth/user"];
            return user != null ? user.name : null;
        },
    },
    methods: {
        /**
         * ログアウト処理
         */
        async logout() {
            alert("logout:1");
            await this.$store.dispatch("auth/logout");
            alert("logout:2");
            this.$router.push("/login");
            alert("logout:3");
        },
    },
};
</script>
