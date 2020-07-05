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
        <transition name="el-fade-in-linear">
            <!--商店-->
            <el-main v-if="status === 1">
                <div class="background">

                    <div v-for="(o, index) in goods" :key="index">
                        <transition name="fade" appear>
                            <div class="item" v-bind:class="{has: curSelect[index] > 0, outOff: o.inventory === 0}">
                                <!--<p>No.{{o.id}} </p>-->
                                <h1>{{o.name}}</h1>
                                <!--suppress HtmlUnknownTarget -->
                                <img :src="o.img" alt="Image">
                                <div style="height: 20px">
                                    <el-tag v-for="t in o.tag" :key="t" class="tag">{{tags[t]}}</el-tag>
                                </div>

                                <h2 v-if="o.inventory > 0">剩余数量：{{o.inventory}}</h2>
                                <h2 v-else>无库存剩余</h2>
                                <p>价格：{{o.price}}</p>
                                <el-input-number v-model="curSelect[index]" :min="0" :max="o.inventory"
                                                 label="选购数量"></el-input-number>
                            </div>
                        </transition>
                    </div>

                </div>

                <!--<el-button-group>-->
                <!--<el-button type="primary" icon="el-icon-arrow-left">上一页</el-button>-->
                <!--<el-button type="primary">下一页<i class="el-icon-arrow-right el-icon&#45;&#45;right"></i></el-button>-->
                <!--</el-button-group>-->

                <h1>总计：{{total}}</h1>
                <el-button type="primary" round :disabled="total <= 0">加入购物车</el-button>
                <el-button type="danger" round :disabled="total <= 0">直接购买</el-button>
            </el-main>
        </transition>
        <transition name="el-fade-in-linear">
            <!--购物车-->
            <el-main v-if="status === 2">
                <div class="background">

                    <div v-for="(o, index) in cart" :key="index">
                        <transition name="fade" appear>
                            <div class="item"
                                 v-on:click="setSelect(index)"
                                 v-bind:class="{has: curSelect[index] === 1}">
                                <!--<p>No.{{o.id}} </p>-->
                                <h1>{{goods[o.gid].name}}</h1>
                                <!--suppress HtmlUnknownTarget -->
                                <img :src="goods[o.gid].img" alt="Image">
                                <div style="height: 20px">
                                    <el-tag v-for="t in goods[o.gid].tag" :key="t" class="tag">{{tags[t]}}</el-tag>
                                </div>

                                <h2>数量：{{o.count}}</h2>
                                <p>价格：{{goods[o.gid].price * o.count}}</p>
                            </div>
                        </transition>
                    </div>

                </div>
                <h1>总计：{{total}}</h1>
                <el-button type="primary" round :disabled="total <= 0">从购物车移除</el-button>
                <el-button type="danger" round :disabled="total <= 0">直接购买</el-button>
            </el-main>
        </transition>
        <transition name="el-fade-in-linear">
            <!--订单-->
            <el-main v-if="status === 3">
                <div class="background">

                    <div v-for="(o, index) in order" :key="index">
                        <transition name="fade" appear>
                            <div class="item">
                                <h1>{{o.time}}</h1>
                                <h2>费用：{{o.totalCost}}</h2>
                                <p>状态：{{statusName(o.status)}}</p>
                                <el-button type="primary" round v-on:click="getDetail(o.id)">详情</el-button>
                            </div>
                        </transition>
                    </div>

                </div>
            </el-main>
        </transition>

        <transition name="el-fade-in-linear">
            <!--订单详情-->
            <el-main v-if="status === 4">
                <div class="background">

                    <div v-for="(o, index) in detail.data" :key="index">
                        <transition name="fade" appear>
                            <div class="item">
                                <h1>{{goods[o.gid].name}}</h1>
                                <!--suppress HtmlUnknownTarget -->
                                <img :src="goods[o.gid].img" alt="Image">
                                <div style="height: 20px">
                                    <el-tag v-for="t in goods[o.gid].tag" :key="t" class="tag">{{tags[t]}}</el-tag>
                                </div>

                                <h2>数量：{{o.count}}</h2>
                                <p>价格：{{o.cost}}</p>
                            </div>
                        </transition>
                    </div>

                </div>
                <h1>总计：{{total}}</h1>
                <el-button type="primary" round :disabled="status !== 0">付款</el-button>
                <el-button type="danger" round :disabled="status === 4">取消订单</el-button>
            </el-main>
        </transition>

    </el-container>
</template>

<script>
    import Vue from 'vue'

    export default {
        name: "Customer",

        props: {
            userId: Number
        },

        data() {
            return {
                status: 0,
                tags: [
                    'abc', 'def'
                ],
                goods: [
                    {id: 1, name: 'a', tag: [0], price: 10, img: require("../assets/logo.png"), inventory: 100},
                    {id: 2, name: 'b', tag: [1], price: 15, img: require("../assets/logo.png"), inventory: 10},
                    {id: 3, name: 'c', tag: [0, 1], price: 1, img: require("../assets/logo.png"), inventory: 0},
                    {id: 4, name: 'd', tag: [], price: 13, img: require("../assets/logo.png"), inventory: 5},
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
                ],
                detail: {
                    id: 1,
                    status: 2,
                    data: [
                        {id: 1, gid: 1, count: 10, cost: 10},
                        {id: 2, gid: 2, count: 3, cost: 30},
                        {id: 3, gid: 3, count: 14, cost: 12}]
                }
            }
        },

        created() {
        },

        computed: {
            total() {
                let sum = 0;
                if (this.status === 1) {
                    for (let i = 0; i < this.curSelect.length; ++i) {
                        sum += this.curSelect[i] == null ? 0 : this.curSelect[i] * this.goods[i].price;
                    }
                } else if (this.status === 2) {
                    for (let i = 0; i < this.cart.length; ++i) {
                        sum += this.curSelect[i] === 1 ? this.cart[i].count * this.goods[this.cart[i].gid].price : 0;
                    }
                } else if (this.status === 4) {
                    for (let i = 0; i < this.detail.length; ++i) {
                        sum += this.detail[i].cost
                    }
                }
                return sum;
            },
        },

        methods: {
            changeSelect(index) {
                this.status = 0
                this.curSelect = []
                // TODO 链接数据库
                setTimeout(() => {
                    this.status = parseInt(index)
                }, 300);
            },

            addToCart() {
                // TODO 加入购物车
            },

            makeOrder() {
                // TODO 制作订单
            },

            setSelect(index) {
                Vue.set(this.curSelect, index, this.curSelect[index] === 1 ? 0 : 1);
                console.log(this.total)
            },

            statusName(st) {
                if (st === 0) {
                    return "未付款"
                } else if (st === 1) {
                    return "已付款"
                } else if (st === 2) {
                    return "已送达"
                } else {
                    return null
                }
            },

            getDetail(id) {
                console.log(id)
                // TODO 链接数据库
                this.status = 0
                setTimeout(() => {
                    this.status = 4
                }, 300)
            }
        }
    }
</script>

<style scoped>
    .background {
        background-color: #e8e8e8;
        display: flex;
        display: -webkit-flex;
        flex-wrap: wrap;
        justify-content: center;
    }

    .item {
        /*阴影效果*/
        filter: progid:DXImageTransform.Microsoft.Shadow(color=#909090, direction=120, strength=4); /*ie*/
        -moz-box-shadow: 2px 2px 10px #909090; /*firefox*/
        -webkit-box-shadow: 2px 2px 10px #909090; /*safari或chrome*/
        box-shadow: 2px 2px 10px #909090; /*opera或ie9*/
    }

    .item {
        width: 300px;
        text-align: center;
        margin: 10px 10px 10px 10px;
        padding: 5px 5px 5px 5px;
        /*圆角效果*/
        border-radius: 20px;
        -webkit-border-radius: 9px;
        -moz-border-radius: 9px;
        background-color: white;
        transition: 1s;
    }

    /*noinspection CssUnusedSymbol*/
    .has {
        background-color: aqua;
    }

    /*noinspection CssUnusedSymbol*/
    .outOff {
        background-color: red;
    }

    /*noinspection CssUnusedSymbol*/
    .fade-enter-active, .fade-leave-active {
        transition: all 1s;
    }

    /*noinspection CssUnusedSymbol*/
    .fade-enter, .fade-leave-to {
        transform: translateY(30px);
        opacity: 0;
    }

    .tag {
        margin-left: 2px;
        margin-right: 2px;
    }
</style>