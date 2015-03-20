<!-- Block testimonial module -->
{include file="$tpl_dir./breadcrumb.tpl"}
<div id="block_testimonials_submit">
  <form class="testimonialForm" id="testimonialForm" name="testimonialForm" method="post" enctype="multipart/form-data" action="addtestimonial.php" >
    {if isset($confirmation)}
      <span class="add-testimonials-confirmation">{l s='Your testimonial was submitted successfully' mod='blockTestimonial'}.</span>
    {/if}
    <fieldset>
     <span class="add-testimonials-title">{l s='We welcome your testimonials - please enter yours using the form below' mod='blockTestimonial'}</span>
      <ol>
        <li class="testim-name"><label for="name">{l s='Name' mod='blockTestimonial'}<em>*</em></label> <input name="testimonial_submitter_name"  value="{$testimonial_submitter_name}" id="testimonial_submitter_name" class="required" minlength="2" /></li>
        <li class="testim-summary"><label for="testimonial_title">{l s='Summary' mod='blockTestimonial'}<em>*</em></label> <input name="testimonial_title" value="{$testimonial_title}" id="testimonial_title" class="required" minlength="2" /></li>
        <li class="testim-body"><label for="testimonial_main_message">{l s='Your Testimonial' mod='blockTestimonial'}</label><textarea  cols="33" rows="5" name="testimonial_main_message" id="testimonial_main_message" class="required" minlength="2" >{$testimonial_main_message}</textarea></li>
        {if $allowupload}
        <li class="testim-img"><label for="testimonial_img">{l s='Optionally Add an Image (up to ' mod='blockTestimonial'}{$imgUpload}{l s='KB filesize)' mod='blockTestimonial'}</label><br/><input type="file" name="testimonial_img" class="testimonial_img" /></li>
        {/if}
      </ol>
    </fieldset>
    {if $recaptcha}
      <fieldset>
        {l s='Please complete this test to prove you are a real person and not a bot' mod='blockTestimonial'}
        {$the_captcha}
      </fieldset>
    {/if}
    <input type="submit" class="button testimonialsubmit" name="testimonial" value="{l s='Submit Testimonial' mod='blockTestimonial'}"  />
  </form>
</div>
<!-- /Block testimonial module -->