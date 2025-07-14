<div class="row">
    @foreach ($permissions as $permission)
        <div class="col-md-4 mb-2">
            <div class="form-check">
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                    class="form-check-input"
                    {{ isset($selectedPermissions) && in_array($permission->id, $selectedPermissions) ? 'checked' : '' }}>
                <label class="form-check-label" for="perm_{{ $permission->id }}">
                    {{ ucwords(str_replace('_', ' ', $permission->name)) }}
                </label>
            </div>
        </div>
    @endforeach
</div>
