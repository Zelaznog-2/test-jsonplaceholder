<script setup >
// import
import { Link } from '@inertiajs/vue3';
import 'vue-good-table-next/dist/vue-good-table-next.css'
import { VueGoodTable } from 'vue-good-table-next';
import { defineProps, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import swal from'sweetalert2';

// props
const props = defineProps({
    data: Array
});

// refs
const form = useForm({})
// data
const columnsData =  [
    {
        label: 'ID',
        field: 'id',
    },{
        label: 'Nombre',
        field: 'name',
    },{
        label: 'Usuario',
        field: 'username',
    },{
        label: 'Email',
        field: 'email',
    },{
        label: 'Sitio Web',
        field: 'website',
    },{
        label: 'Total Posts',
        field: 'totalPosts',
    },{
        label: 'Acciones',
        field: 'action',
        sortable: false,
        searchable: false,
    }
]


// methods
const deleteItem = (item) => {
    swal.fire({
        title: `¿Estas seguro que quiere eliminar al usuario ${item.name}?`,
        text: "¡No podrás revertir esto!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#98C44B',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, eliminar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
        form.delete(route('users.destroy', item.id), {
            preserveScroll: true,
            onSuccess: () => {
            swal.fire(
                'Eliminado!',
                'El usuario ha sido eliminado.',
                'success'
            )
            },
            onError: () => {
            swal.fire(
                'Error!',
                'El usuario no ha sido eliminado.',
                'error'
            )
            }
        })
        }
    })
}

</script>
<template>
    <div class="p-4">
        <vue-good-table 
            :columns="columnsData" 
            :rows="data" 
            :pagination="true" 
            :pagination-options="{
                enabled: true,
                nextLabel: 'Siguiente',
                prevLabel: 'Anterior',
                rowsPerPageLabel: 'Filas por paginas',
                ofLabel: 'de',
                pageLabel: 'paginas',
                allLabel: 'Todos',
                rowsPerPage: 5,
                currentPage: 1,
            }"
            :search-options="{
                enabled: true,
                placeholder: 'Buscar...',
            }" 
            :fixed-header="true" >
            <template template v-slot:emptystate="props">
                <h6 class="text-center">No hay registro</h6>
            </template>
            <template v-slot:table-row="props">
                <span v-if="props.column.field == 'action'">
                    <button class="mr-4 ml-4 text-blue-600">
                        <Link :href="route('users.edit', [props.row.id])">
                        Editar
                        </Link>
                    </button>
                    <button class="ml-4 text-red-600" @click="deleteItem(props.row)">
                        Eliminar
                    </button>
                </span>
                <span v-else>
                    {{ props.formattedRow[props.column.field] }}
                </span>
            </template>
        </vue-good-table>
    </div>
</template>