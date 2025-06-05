<script setup>
// import
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { defineProps } from 'vue';
import Form from './partials/Form.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Swal from 'sweetalert2';


// props
const props = defineProps({
    post: Object | null,
    users: Array
});


const form = useForm({
    id: 0,
    user_id: 0,
    title: '',
    body: '',
    isRedirect: false
})

const formComment = useForm({})


// methods
const setData = () => {
    if (props.post) {
        form.id = props.post.id;
        form.body = props.post.body;
        form.title = props.post.title;
        form.user_id = props.post.user_id;        
    }
}

const deleteComment = (commentId) => {
    formComment.delete(route('comments.destroy', commentId), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire(
                'Eliminado!',
                'El comentario ha sido eliminado.',
                'success'
            )
        },
        onError: () => {
            Swal.fire(
                'Error!',
                'El comentario no ha sido eliminado.',
                'error'
            )
        }
    })
}


const submit = () => {

    form.isRedirect = true;

    if (form.id === 0) {
        form.post(route('posts.store'), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire(
            'Actualizado!',
            'El post ha sido creado.',
            'success'
            )
        },
        onError: () => {
            Swal.fire(
            'Error!',
            'El post no ha sido creado.',
            'error'
            )
        }
        })
    } else {
        form.put(route('posts.update', form.id), {
        preserveScroll: true,
        onSuccess: () => {
            Swal.fire(
            'Actualizado!',
            'El post ha sido actualizado.',
            'success'
            )
        },
        onError: () => {
            Swal.fire(
            'Error!',
            'El post no ha sido actualizado.',
            'error'
            )
        }
        })
    }
}

// onMounted
setData()

</script>

<template>
    <Head :title="`${form.id === 0 ? 'Nuevo' : 'Actualizar'} Post`" />

    <AuthenticatedLayout>
        <template #header>
        <h1>{{ form.id === 0 ? 'Nuevo' : 'Actualizar' }} Post</h1>
        </template>

        <div class="overflow-x-auto">
        <div class="inline-block min-w-full align-middle">
            <div class="overflow-hidden shadow">

                <Form :form="form" :users="users" @submit="submit()"/>
            </div>
            <h3 class="p-4 mt-5 uppercase">Commentarios</h3>
            <div class="flex flex-col my-2 p-4 border-bottom" v-for="comment in props.post.comments" :key="comment.id">
                <div class="w-1/2 ">
                    <p class="text-gray-700">{{ comment.body }}</p>
                    <p class="text-gray-500 text-sm">Por: {{ comment.name }}</p>
                </div>
                <div class="w-1/2 flex justify-end">
                    <button @click="deleteComment(comment.id)" class="text-red-500 hover:text-red-700">Eliminar</button>
                </div>
            </div>
                
        </div>
        </div>

    </AuthenticatedLayout>
</template>