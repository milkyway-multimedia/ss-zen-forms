<$Tag $AttributesHTML>
<% if $Tag == 'fieldset' && $Legend %>
    <legend>$Legend</legend>
<% end_if %>

<% loop $FieldList %>
    <% if $ColumnCount %>
        <div class="column-{$ColumnCount} $FirstLast">
            $FieldHolder
        </div>
    <% else %>
        $FieldHolder
    <% end_if %>
<% end_loop %>
</$Tag>
