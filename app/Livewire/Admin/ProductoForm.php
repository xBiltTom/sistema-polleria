<?php

namespace App\Livewire\Admin;

use App\Models\Producto;
use App\Models\CategoriaProducto;
use Livewire\Component;
use Livewire\Attributes\On;

class ProductoForm extends Component
{
    public $productos = [];
    public $categorias = [];
    public $showForm = false;
    public $editingId = null;

    public $nombre = '';
    public $descripcion = '';
    public $idCategoriaProducto = '';
    public $precioVenta = '';

    protected $rules = [
        'nombre' => 'required|string|max:45',
        'descripcion' => 'nullable|string|max:100',
        'idCategoriaProducto' => 'required|exists:categoria_producto,idCategoriaProducto',
        'precioVenta' => 'required|numeric|min:0',
    ];

    #[On('categoriaProductoGuardada')]
    public function recargarCategorias()
    {
        $this->categorias = CategoriaProducto::all()->toArray();
    }

    public function mount()
    {
        $this->loadProductos();
        $this->categorias = CategoriaProducto::all()->toArray();
    }

    public function loadProductos()
    {
        $this->productos = Producto::with('categoria')->get()->toArray();
    }

    public function openForm()
    {
        $this->showForm = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->nombre = '';
        $this->descripcion = '';
        $this->idCategoriaProducto = '';
        $this->precioVenta = '';
        $this->editingId = null;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingId) {
            $producto = Producto::find($this->editingId);
            $producto->update([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'idCategoriaProducto' => $this->idCategoriaProducto,
                'precioVenta' => $this->precioVenta,
            ]);
            $this->dispatch('notify', 'Producto actualizado exitosamente');
        } else {
            Producto::create([
                'nombre' => $this->nombre,
                'descripcion' => $this->descripcion,
                'idCategoriaProducto' => $this->idCategoriaProducto,
                'precioVenta' => $this->precioVenta,
                'stock' => 0,
            ]);
            $this->dispatch('notify', 'Producto creado exitosamente');
        }

        $this->loadProductos();
        $this->showForm = false;
        $this->resetForm();
    }

    public function edit($id)
    {
        $producto = Producto::find($id);
        $this->editingId = $id;
        $this->nombre = $producto->nombre;
        $this->descripcion = $producto->descripcion;
        $this->idCategoriaProducto = $producto->idCategoriaProducto;
        $this->precioVenta = $producto->precioVenta;
        $this->showForm = true;
    }

    public function delete($id)
    {
        Producto::find($id)->delete();
        $this->loadProductos();
        $this->dispatch('notify', 'Producto eliminado');
    }

    public function render()
    {
        return view('livewire.admin.producto-form');
    }
}
