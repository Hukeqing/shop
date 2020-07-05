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
            <Shop v-if="status === 1" :goods="goods" :tags="tags"></Shop>
            <Cart v-if="status === 2" :goods="goods" :tags="tags" :cart="cart"></Cart>
            <Order v-if="status === 3" :goods="goods" :tags="tags" :order="order"></Order>
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
            Order,
            Shop,
            Cart
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
                ],
                curSelect: [],
                cart: [
                    {id: 1, gid: 1, count: 10},
                    {id: 2, gid: 2, count: 3},
                    {id: 3, gid: 3, count: 14}
                ],
                order: [
                    /**
                     * 0 for make order
                     * 1 for paid
                     * 2 for send
                     */
                    {id: 1, totalCost: 10, status: 0, time: "2020-7-5"},
                    {id: 2, totalCost: 230, status: 1, time: "2020-7-6"},
                    {id: 3, totalCost: 123, status: 2, time: "2020-7-7"}
                ]
            }
        },

        created() {
        },

        computed: {
        },

        methods: {
            changeSelect(index) {
                this.status = 0
                this.curSelect = []
                // TODO 链接数据库
                setTimeout(() => {
                    this.status = parseInt(index)
                }, 300);
            }
        }
    }
</script>

<style scoped>
</style>