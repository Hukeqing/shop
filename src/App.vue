<template>
    <div id="app">
        <Login v-if="status===0"></Login>
        <Customer v-if="status === 1"></Customer>
    </div>
</template>

<script>
    // import HelloWorld from './components/HelloWorld.vue'

    import Login from "@/components/Login";
    import Customer from "@/components/Customer";

    export default {
        name: 'App',
        components: {
            Login,
            Customer
        },

        data() {
            return {
                /**
                 * 0 for login and register
                 */
                status: 1
            }
        }
    }
</script>

<style>
    #app {
        /*font-family: Avenir, Helvetica, Arial, sans-serif;*/
        font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 60px;
    }
</style>
