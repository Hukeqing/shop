<template>
    <transition name="el-fade-in-linear">
        <!--购物车-->
        <div>
            <div class="background">

                <div v-for="(o, index) in cart" :key="index">
                    <transition name="fade" appear>
                        <div class="item round"
                             v-on:click="setSelect(index)"
                             v-bind:class="{has: curSelect[index] === 1}">
                            <!--<p>No.{{o.id}} </p>-->
                            <h1>{{goods[o.gid].name}}</h1>
                            <!--suppress HtmlUnknownTarget -->
                            <img :src="goods[o.gid].img" alt="Image">
                            <div style="height: 20px">
                                <template v-for="t in goods[o.gid].tag">
                                    <el-tag v-if="tags[t].work===true" :key="t" class="tag">
                                        {{tags[t].tag}}
                                    </el-tag>
                                </template>
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
        </div>
    </transition>
</template>

<script>
    import Vue from "vue";

    export default {
        name: "Cart",

        data() {
            return {
                curSelect: [],
            }
        },

        props: {
            tags: Array,
            goods: Array,
            cart: Array
        },
        computed: {
            total() {
                let sum = 0;
                for (let i = 0; i < this.cart.length; ++i) {
                    sum += this.curSelect[i] === 1 ? this.cart[i].count * this.goods[this.cart[i].gid].price : 0;
                }
                return sum;
            }
        },

        methods: {
            setSelect(index) {
                Vue.set(this.curSelect, index, this.curSelect[index] === 1 ? 0 : 1);
                console.log(this.total)
            }
        }
    }
</script>

<style scoped>
</style>