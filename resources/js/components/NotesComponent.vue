<template>
	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-10">
				<div class="card card-default">
					<div class="card-header">
						<h3 class="float-left">Notas</h3>
						<button type="button" class="btn btn-success float-right" @click="newNoteModal">
						  Nueva Nota
						</button>

						<!-- Modal -->
						<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
						        <h5 class="modal-title" id="exampleModalLabel">
						        	<span v-show="!viewMode">Nueva Nota</span>
						        	<span v-show="viewMode">Ver Nota #{{ noteId }}</span>
						        </h5>
						        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
						          <span aria-hidden="true">&times;</span>
						        </button>
						      </div>
						      <form @submit.prevent="createNote">
							      <div class="modal-body">
							        <div class="form-group">
							        	<label>Título</label>
							        	<input v-model="form.title" type="text" name="username" class="form-control" :class="{ 'is-invalid': form.errors.has('title') }" placeholder="Ingrese título" :readonly="viewMode" id="title">
							        	<has-error :form="form" field="title"></has-error>
							        </div>

							        <div class="form-group">
							        	<label>Descripción</label>
							        	<textarea v-model="form.description" class="form-control" :class="{ 'is-invalid': form.errors.has('description') }" placeholder="Ingrese descripción" :readonly="viewMode">
							        	</textarea>
							        	<has-error :form="form" field="description"></has-error>
							        </div>

							        <div class="form-group">
							        	<div class="row">
							        		<div class="col-md-7">
							        			<label>Categoría</label>
							        			<select name="category" v-model="form.category" id="category" class="form-control" :class="{ 'is-invalid': form.errors.has('category') }" :disabled="viewMode">
							        				<option value="">Seleccione categoría</option>
							        				<option v-for="category in categories" :key="category" :value="category">
							        					{{ category.toUpperCase() }}
							        				</option>
							        			</select>
							        			<has-error :form="form" field="category"></has-error>
							        		</div>
							        		<div class="col-md-5">
							        			<label>Estado</label>
							        			<select name="status" v-model="form.status" id="status" class="form-control" :class="{ 'is-invalid': form.errors.has('status') }" :readonly="viewMode">
							        				<option value="1">Habilitada</option>
							        				<option value="0">Deshabilitada</option>
							        			</select>
							        			<has-error :form="form" field="status"></has-error>
							        		</div>
							        	</div>
							        	
							        </div>

							        <div class="form-group">
							        	<label>Etiquetas</label>
							        	<v-select v-model="form.tags" taggable multiple :options="tags" :disabled="viewMode" />
							        </div>
							      </div>
						      <div class="modal-footer">
						        <button type="submit" class="btn btn-success" v-show="!viewMode">Guardar</button>
						        <button type="button" class="btn btn-danger" data-dismiss="modal">
						        	<span v-show="!viewMode">Cancelar</span>
						        	<span v-show="viewMode">Cerrar</span>
					        	</button>
						      </div>
						      </form>
						    </div>
						  </div>
						</div>
					</div>

					<div class="card-body table-responsive">
						<table class="table table-sm table-hover">
							<thead>
								<tr>
									<th class="text-center">#</th>
									<th class="text-center">Fecha</th>
									<th class="text-center">Título</th>
									<th class="text-center">Categoría</th>
									<th class="text-center">Estado</th>
									<th class="text-center">Acción</th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="note in notes" :key="note.id">
									<td class="align-middle text-center">{{ note.id }}</td>
									<td class="align-middle text-center">{{ note.created }}</td>
									<td>{{ note.title }}</td>
									<td class="align-middle text-center">{{ note.category.toUpperCase() }}</td>
									<td class="align-middle text-center">{{ note.status_desc }}</td>
									<td class="align-middle text-center">
										<a href="#" @click="viewModal(note)" class="btn btn-info text-white btn-sm">
											Ver
										</a>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="col-12">
						<button class="btn btn-block btn-warning mb-3" @click="loadMore" v-show="notes.length >= (limit * start)">
							Cargar más notas
						</button>	
					</div>
				</div>
			</div>
		</div>
	</div>
</template>

<script>
	export default {
		data() {
			return {
				viewMode: false,
				noteId: '',
				notes: [],
				tags: [],
				categories: [],
				limit: 5,
				start: 0,
				form: new Form({
					title: '',
					description: '',
					category: '',
					status: '',
					tags: [],
				})
			}
		},
		methods: {
			newNoteModal(){
				//Deshabilito modo vista
				this.viewMode = false;
				this.noteId = '';
				this.tags = [];
				//Reinicio el formulario
				this.form.reset();
				//Muestro el dialogo
				$('#exampleModal').modal('show');
				//Evento para el foco
				$('#exampleModal').on('shown.bs.modal', function () {
			  		$('#title').trigger('focus')
				})
			},
			viewModal(note){
				//Seteo módo lectura
				this.viewMode = true;
				this.noteId = note.id;
				this.tags = note.tags;
				this.form.reset();
				$('#exampleModal').modal('show');
				
				this.form.fill(note);
			},
			loadCategories(){
				axios.get('api/categories')
					.then(({data}) => (this.categories = data.data));
			},
			loadNotes(){
				axios.get('api/notes')
					.then(({data}) => (this.notes = data.data));
			},
			loadMore(){
				//Controlo
				if(this.notes.length >= this.limit){
					this.$Progress.start();
					//Incremento el índice
					this.start += this.limit;
					//Hago la consulta
					axios.get(`api/notes?start=${this.start}`)
						.then(({data}) => {
							this.notes = [...this.notes, ...data.data];
							this.$Progress.finish();
						}).catch(error => {
							this.$Progress.finish();
						});
				}
			},
			createNote(){
				this.$Progress.start();
				this.form.post('api/notes')
					.then(() => {
						Fire.$emit('noteCreated');
						$('#exampleModal').modal('hide');
						toast.fire({
						  icon: 'success',
						  title: 'Nota creada'
						});
						this.$Progress.finish();
					})
					.catch(() => {
						this.$Progress.finish();
					});
				
			}
		},
		created(){
			//Cargo la notas
			this.loadNotes();
			//Cargo las categorías
			this.loadCategories();
			//Si se generó una nueva note, recargo
			Fire.$on('noteCreated', () => {
				this.loadNotes();
			});
		},
		mounted(){
			console.log('Montado');
		}
	}
</script>