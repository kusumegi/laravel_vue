<template>
    <div class="main-container">
        <h2>ログイン</h2>
        <v-form ref="form" v-model="valid" :lazy-validation="lazy">
            <v-text-field
                v-model="form.email"
                :rules="emailRules"
                label="E-mail"
                required
                autocomplete="email"
            ></v-text-field>
            <v-text-field
                v-model="form.password"
                :rules="passwordRules"
                label="Password"
                required
                type="password"
                autocomplete="password"
            ></v-text-field>
            <v-btn :disabled="!valid" color="success" @click="login"
                >ログイン</v-btn
            >
        </v-form>
    </div>
</template>

<script>
// import Header from "../components/Header.vue";

export default {
    components: {
        // Header
    },
    metaInfo: {
        title: "TODOリスト ログイン",
        htmlAttrs: {
            lang: "ja",
        },
    },
    created() {
        // 画面表示時、既にログイン済みならTODOリストへ遷移する
        if (this.$store.getters["auth/user"] !== null) {
            this.$router.push("/todo");
            return;
        }
    },
    data() {
        return {
            valid: true,
            form: {
                email: "",
                password: "",
            },
            emailRules: [
                (v) => !!v || "E-mailを入力してください",
                (v) =>
                    /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                    "E-mailを正しく入力してください",
            ],
            passwordRules: [(v) => !!v || "パスワードを入力してください"],
        };
    },
    methods: {
        /**
         * ログイン時の処理
         */
        async login() {
            try {
                await this.$store.dispatch("auth/login", this.form);
                this.$router.push("/todo");
            } catch (error) {
                alert(
                    `ログイン失敗：${JSON.stringify(
                        error.response.data.errors
                    )}`
                );
            }
        },
    },
};
</script>

<style>
.main-container {
    max-width: 500px;
    margin: auto;
}
</style>
