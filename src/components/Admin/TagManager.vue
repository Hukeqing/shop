<template>
    <transition name="el-fade-in-linear">
        <div>
            <div class="background">
                <div v-for="tag in tags" :key="tag.id">
                    <transition name="fade" appear>
                        <div class="item round-small">
                            <el-tag class="tag">{{tag.tag}}</el-tag>
                            <el-button type="mini" round>{{tagFunc(tag.work)}}</el-button>
                        </div>
                    </transition>
                </div>
            </div>
            <div>
                <p>
                    新建标签：
                    <el-input style="width: 200px; margin-right: 10px"
                              placeholder="请输入内容"
                              v-model="newTag"
                              clearable>
                    </el-input>
                    <el-button type="primary" round>添加</el-button>
                </p>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        name: "TagManager",

        data() {
            return {
                tags: [
                    {id: 1, tag: 'abc', work: true},
                    {id: 2, tag: 'def', work: true},
                    {id: 3, tag: 'aaa', work: false}
                ],
                newTag: "",
            }
        },

        methods: {
            tagFunc(work) {
                if (work) {
                    return "删除"
                } else {
                    return "恢复"
                }
            },
        }
    }
</script>

<style scoped>

</style>