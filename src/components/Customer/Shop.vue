<template>
    <transition name="el-fade-in-linear">
        <!--商店-->
        <div>
            <div class="background">
                <div v-for="(o, index) in goods" :key="index">
                    <transition name="fade" appear>
                        <div class="item round" v-bind:class="{has: curSelect[index] > 0, outOff: o.inventory === 0}">
                            <!--<p>No.{{o.id}} </p>-->
                            <h1>{{o.name}}</h1>
                            <!--suppress HtmlUnknownTarget -->
                            <img :src="o.img" alt="Image">
                            <div style="height: 20px">
                                <template v-for="t in o.tag">
                                    <el-tag v-if="tags[t].work===true" :key="t" class="tag">
                                        {{tags[t].tag}}
                                    </el-tag>
                                </template>
                            </div>

                            <h3 v-if="o.inventory > 0">剩余数量：{{o.inventory}}</h3>
                            <h2 v-else>无库存剩余</h2>
                            <p>价格：{{o.price}}</p>
                            <p>
                                购买：
                                <el-input-number v-model="curSelect[index]" :min="0" :max="o.inventory"
                                                 label="选购数量"></el-input-number>
                            </p>
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
        </div>
    </transition>
</template>

<script>
    export default {
        name: "shop",

        data() {
            return {
                curSelect: [],
            }
        },

        props: {
            tags: Array,
            goods: Array,
        },

        computed: {
            total() {
                let sum = 0;
                for (let i = 0; i < this.curSelect.length; ++i) {
                    sum += this.curSelect[i] == null ? 0 : this.curSelect[i] * this.goods[i].price;
                }
                return sum;
            }
        },

        methods: {
            addToCart() {
                // TODO 加入购物车
            },

            makeOrder() {
                // TODO 制作订单
            },
        }
    }
</script>

<style scoped>
</style>