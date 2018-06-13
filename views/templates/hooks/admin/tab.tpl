<div id="product-videos" class="panel product-tab">
    <input type="hidden" name="submitted_tabs[]" value="productvideo" />
    <h3 class="tab"> <i class="icon-video"></i> {l s='Product Videos'}</h3>
<div class="form-group">
        {if $entryexists}
            <label class="control-label col-lg-2">
                {l s='Enabled'}
            </label>
        
        {else}
            <label class="control-label col-lg-2">
                {l s='Video not available'}
            </label>
        {/if}
        
        <!--<h1>{$output}</h1>-->
        {if $entryexists}
            <div class="col-lg-9">
                <span class="switch prestashop-switch fixed-width-lg">
                    <input type="radio" name="active_video" id="active_on_video" value="1" {if $status==1 }checked="checked" {/if} />
                    <label for="active_on_video" class="radioCheck">
                        {l s='Yes'}
                    </label>
                    <input  type="radio" name="active_video" id="active_off_video" value="0" {if $status==0 }checked="checked" {/if} />
                    <label for="active_off_video" class="radioCheck">
                        {l s='No'}
                    </label>
                    <a class="slide-button btn"></a>
                </span>
            </div>
        {/if}
    </div>
    {if $ps_version = 16}
    <div class="panel-footer">
        <a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}{if isset($smarty.request.page) && $smarty.request.page > 1}&amp;submitFilterproduct={$smarty.request.page|intval}{/if}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save'}</button>
        <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save and stay'}</button>
    </div>
    {/if}
</div>

