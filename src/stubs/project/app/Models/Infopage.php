<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Marinar\Marinar\Traits\AddVariable;
use Marinar\Marinar\Traits\MacroableModel;

class Infopage extends Model
{
    use MacroableModel;
    use AddVariable;

    // @HOOK_TRAITS

    protected $fillable = ['active', 'site_id', 'parent_id', 'system'];

    protected static function boot() {
        parent::boot();
        static::deleting( static::class.'@onDeleting_infopages' );

        // @HOOK_CONSTRUCT
    }

    public function onDeleting_infopages($model) {
        $this->loadMissing('children');
        foreach($model->children as $infopage) {
            $infopage->delete();
        }
    }

    public function getParents() {
        $return = array();
        $this->loadMissing('parent.parent');
        if( !$this->parent->exists() )
            return $return;
        $return[ $this->parent->id ] = $this->parent;
        return array_merge($return, $this->parent->parent);
    }

    public function getLevel() {
        return count($this->getParents());
    }

    public function parent() {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    public function children() {
        return $this->hasMany( static::class, 'parent_id', 'id');
    }

    public function childrenQry($bldQry = null) {
        if(is_null($bldQry)) return $this->children();
        return $bldQry->where('parent_id', $this->id);
    }
}
