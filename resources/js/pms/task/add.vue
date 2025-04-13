<template>
    <div class="page-wrap property-add">
        <div class="page-title mb-4">
            <div class="row gy-3 align-items-center">
                <div class="col align-self-end">
                    <h1 class="h2 mb-0">{{ route.params.id ? 'Modify Task' : 'Add Task' }}</h1>
                </div>
                <div class="col-auto">
                    <router-link :to="{name: 'list-task'}" class="btn rounded-pill btn-secondary-light">
                        <i class="icon-list me-2"></i>
                        Manage
                    </router-link>
                </div>
            </div>
        </div>
        <section class="section">
            <div class="d-flex justify-content-center py-5" v-if="isLoading">
                <div class="spinner-border" role="status"></div>
            </div>

            <div class="page-content" v-else>
                <Form action="" @submit="onSubmit" v-slot="{errors}">
                    
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form-group">
                                        <label for="">Title<span class="text-danger">*</span></label>
                                        <Field
                                            type="text"
                                            name="title"
                                            :class="{'border-danger': errors.title}"
                                            class="form-control"
                                            rules="required"
                                            placeholder="Title"
                                            v-model="tasksEditData.title"
                                        />
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="form-group">
                                        <label for="">Due Date<span class="text-danger">*</span></label>
                                        <Field name="due_date" v-model="due_date" rules="required" v-slot="{ field }">
                                            <DatePicker 
                                                v-bind="field"
                                                v-model="due_date" 
                                                :format="format" 
                                                :enable-time-picker="false" 
                                                :max-date="new Date()"
                                                :input-class-name="`form-control ${errors.due_date ? 'border-danger' : ''}`" 
                                                prevent-min-max-navigation
                                                auto-apply 
                                            />
                                        </Field>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12">
                                    <div class="form-group">
                                        <label for="">Description</label>
                                        <ckeditor
                                            name="description" 
                                            :class="{'border-danger': errors.description}"
                                            v-model:data="tasksEditData.description"
                                            rules="required"
                                        />
                                    </div> 
                                </div>
                                
                                <div class="col-12">
                                    <div class="form-group">
                                        <div class="form-check form-switch">
                                            <input 
                                                name="status" 
                                                class="form-check-input"
                                                type="checkbox"
                                                v-model="vStatusChecked"
                                                true-value="1"
                                                false-value="0"
                                            >
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                       
                    
                        <div class="col-12">
                            <div class="form-group mb-0">
                                <button class="btn btn-save btn-primary">
                                    <div v-if="isSubmitLoading" class="spinner-border spinner-border-sm" role="status"></div>
                                    <span v-else>SUBMIT</span>
                                </button>
                            </div>
                        </div>
                    
                </Form>
            </div>
        </section>
    </div>
</template>
<script setup>
    import axios from 'axios'
    import ckeditor from '@components/ckeditor.vue'
    import dayjs from 'dayjs'
    import { ref, onMounted, defineAsyncComponent } from 'vue'
    import { useRouter, useRoute } from 'vue-router'
    import { Form, Field, } from 'vee-validate'
    import { toast } from '@utils/toast'
    const MultiSelect = defineAsyncComponent(() => import('@components/multi-select.vue'))
    const route = useRoute()
    const router = useRouter()
    const vStatusChecked = ref(1)
    const tasksEditData = ref({})
    const submitApiUrl = ref(null)
    const isLoading = ref(false)
    const isSubmitLoading = ref(false)
    const due_date = ref()

   

    const format = (due_date) => {
        return dayjs(due_date).format('D MMM, YYYY')
    }

    // For location edit
    const taksEdit = () => {
        isLoading.value = true
        axios.get(`/api/task-detail/${route.params.id}`).then(res => {
            if(res.data.status){
                if(res.data.data){
                    tasksEditData.value = res.data.data,
                    due_date.value = tasksEditData.value.due_date
                }
                setTimeout(() => {
                    isLoading.value = false
                }, 400)
            }else{
                tasksEditData.value = []
                isLoading.value = false   
            } 
        }).catch(error => {
            isLoading.value = false
            console.log(error);
        })
    }

    // For form on submit
    const onSubmit = (v, {resetForm}) => {
        isSubmitLoading.value = true
        submitApiUrl.value = route.params.id ? `/api/save-task-detail/${route.params.id}` : '/api/save-task-detail'
        axios.post(submitApiUrl.value, {
            title: v.title,
            due_date: dayjs(v.due_date).format('YYYY-MM-DD'), 
            description: v.description,
            status: vStatusChecked.value
        }).then(res => {
            if(res.data.status){
                toast(res.data.message, 'success').show()
                setTimeout(() => {
                    isSubmitLoading.value = false
                    router.push({name: 'list-task'})
                }, 400)
            }
        }).catch(error => {
            toast(error.response.data.message, 'error').show()
            isSubmitLoading.value = false
        })

    }

    onMounted(() => {
        taksEdit()
    })


</script>
