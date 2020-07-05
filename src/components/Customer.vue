<template>
    <el-container>
        <el-aside width="200px">
            <el-menu :default-active="'0'" @select="changeSelect">
                <el-menu-item index="1">
                    <span slot="title">商店</span>
                </el-menu-item>
                <el-menu-item index="2">
                    <span slot="title">购物车</span>
                </el-menu-item>
                <el-menu-item index="3">
                    <span slot="title">订单</span>
                </el-menu-item>
            </el-menu>
        </el-aside>
        <el-main>
            <Shop v-if="status === 1" :goods="goods" :tags="tags" :user-id="userId"></Shop>
            <Cart v-if="status === 2" :goods="goods" :tags="tags" :user-id="userId" v-on:flush="flush"></Cart>
            <Order v-if="status === 3" :goods="goods" :tags="tags"></Order>
        </el-main>
    </el-container>
</template>

<script>
    import Shop from "./Customer/Shop";
    import Cart from "./Customer/Cart";
    import Order from "./Customer/Order";

    export default {
        name: "Customer",
        components: {
            Shop,
            Cart,
            Order
        },
        props: {
            userId: Number
        },

        data() {
            return {
                status: 0,
                tags: [
                    {tag: 'abc', work: true},
                    {tag: 'def', work: true},
                    {tag: 'aaa', work: false}
                ],
                goods: [
                    {id: 1, name: 'a', tag: [0, 2], price: 10, img: require("../assets/logo.png"), inventory: 100},
                    {id: 2, name: 'b', tag: [1, 2], price: 15, img: require("../assets/logo.png"), inventory: 10},
                    {id: 3, name: 'c', tag: [0, 1, 2], price: 1, img: require("../assets/logo.png"), inventory: 0},
                    {id: 4, name: 'd', tag: [2], price: 13, img: require("../assets/logo.png"), inventory: 5},
                ]
            }
        },

        created() {
            fetch('http://119.3.172.223/vue/shopAPI/goods.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.goods = json.data
            }).catch(() => {
                this.$message.error('网络异常')
            })

            fetch('http://119.3.172.223/vue/shopAPI/tags.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.goods = json.data
            }).catch(() => {
                this.$message.error('网络异常')
            })
        },

        computed: {},

        methods: {
            changeSelect(index) {
                this.status = 0
                setTimeout(() => {
                    this.status = parseInt(index)
                }, 300);
            },

            flush() {
                let lastStatus = this.status
                this.status = 0
                setTimeout(() => {
                    this.status = parseInt(lastStatus)
                }, 300);
            }
        }
    }
</script>

<style scoped>
</style>