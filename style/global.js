





// Variables used as maps to store the original text and html of some fields whose values get replaced by
// renderer previews.
var origHtml = new Object();
var origText = new Object();
var origPreviewLink = new Object();

/*
 * Used to submit and restore a renderer preview. This relies of the DWR library and the generated
 * RendererPreviewAjaxUtil.js bean, which performs the server-side access.
 */
function toggleRenderPreview(fieldId, fieldName, rendererType, issueKey)
{
    if(origHtml[fieldId] == null)
    {
        // this is a hack for Safari/dwr since safari does not seem to generate an XMLHTTPRequest correctly see JRA-8354
        if(encodeURI(DWRUtil.getValue(fieldId)).length > 2500 && navigator.userAgent.indexOf('Safari') >= 0)
        {
          // first open the new window
          window.open('', 'wiki_renderer_preview', 'width=780, height=575, resizable, scrollbars=yes');

          // then form submit to the window, we have to form submit because the error we are trying to get around
          // is the max size limit for a GET param value
          var previewForm = document.createElement('form');
          previewForm.action = '/secure/WikiRendererPreviewAction.jspa?rendererType='+ rendererType + '&issueKey=' + issueKey + '&fieldName='+ encodeURI(fieldName);
          previewForm.method = 'POST';
          previewForm.target = 'wiki_renderer_preview';
          var unrenderedMarkup = document.createElement('input');
          unrenderedMarkup.name = 'unrenderedMarkup';
          unrenderedMarkup.type = 'hidden';
          unrenderedMarkup.value= DWRUtil.getValue(fieldId);
          previewForm.appendChild(unrenderedMarkup);

          var bodys = document.getElementsByTagName('BODY');
          bodys[0].appendChild(previewForm);
          previewForm.submit();
        }
        else
        {
          showWaitImage(true, fieldId);
          RendererPreviewAjaxUtil.getPreviewHtml(renderPreviewCallback(fieldId), rendererType, DWRUtil.getValue(fieldId), issueKey);
        }
    }
    else
    {
        document.getElementById(fieldId + "-temp").name = fieldId + "-temp";
        // clear the height before we reset
        xHeight(fieldId + "-edit", null);
        DWRUtil.setValue(fieldId + "-edit", origHtml[fieldId]);
        DWRUtil.setValue(fieldId, origText[fieldId]);
        origHtml[fieldId] = null;
        document.getElementById(fieldId + "-edit").className = "";
        DWRUtil.setValue(fieldId + "-preview_link", "<img alt='"+ "Preview" +"' title='" + "Preview" +"' class='unselectedPreview' border='0' width='18' height='18' src='/images/icons/fullscreen.gif'>");
    }
    return false;
}

/*
* This is the call-back function for the AJAX call to the RendererPreviewAjaxUtil getPreviewHtml call. This
* function replaces the input with the renderered content.
*/
var renderPreviewCallback = function(fieldId)
{
    return function(data)
    {
        var origHeight = xHeight(fieldId + "-edit", null);
        origHtml[fieldId] = DWRUtil.getValue(fieldId + "-edit");
        origText[fieldId] = DWRUtil.getValue(fieldId);
        DWRUtil.setValue(fieldId + "-temp", origText[fieldId]);
        document.getElementById(fieldId + "-temp").name = fieldId;
        showWaitImage(false, fieldId );
        DWRUtil.setValue(fieldId + "-preview_link", "<img alt='"+ "Edit' title='" + "Edit" + "' class='selectedPreview' width='18' height='18' src='/images/icons/fullscreen.gif'>");
        document.getElementById(fieldId + "-edit").className = "previewClass";
        DWRUtil.setValue(fieldId + "-edit", data);
        var newHeight = xHeight(fieldId + "-edit", null);
        if(newHeight < origHeight)
        {
           xHeight(fieldId + "-edit", origHeight);
        }
    }
}

function showWaitImage(flag, fieldId)
{
    var waitImageHtml = "<img id='" + fieldId + "-wait_image' alt='Wait Image' border='0' src='/images/icons/wait.gif'>";
    if(flag)
    {
      origPreviewLink[fieldId] = DWRUtil.getValue(fieldId + "-preview_link_div");
      DWRUtil.setValue(fieldId + "-preview_link_div", waitImageHtml);
    }
    else
    {
      DWRUtil.setValue(fieldId + "-preview_link_div", origPreviewLink[fieldId]);
      origPreviewLink[fieldId] = null;
    }
}

// xHeight, Copyright 2001-2005 Michael Foster (Cross-Browser.com)
// Part of X, a Cross-Browser Javascript Library, Distributed under the terms of the GNU LGPL

function xHeight(e,h)
{
  if(!(e=xGetElementById(e))) return 0;
  if (xNum(h)) {
    if (h<0) h = 0;
    else h=Math.round(h);
  }
  else h=-1;
  var css=xDef(e.style);
  if (e == document || e.tagName.toLowerCase() == 'html' || e.tagName.toLowerCase() == 'body') {
    h = xClientHeight();
  }
  else if(css && xDef(e.offsetHeight) && xStr(e.style.height)) {
    if(h>=0) {
      var pt=0,pb=0,bt=0,bb=0;
      if (document.compatMode=='CSS1Compat') {
        var gcs = xGetComputedStyle;
        pt=gcs(e,'padding-top',1);
        if (pt !== null) {
          pb=gcs(e,'padding-bottom',1);
          bt=gcs(e,'border-top-width',1);
          bb=gcs(e,'border-bottom-width',1);
        }
        // Should we try this as a last resort?
        // At this point getComputedStyle and currentStyle do not exist.
        else if(xDef(e.offsetHeight,e.style.height)){
          e.style.height=h+'px';
          pt=e.offsetHeight-h;
        }
      }
      h-=(pt+pb+bt+bb);
      if(isNaN(h)||h<0)
      {
        return;
      }
      else
      {
        e.style.height=h+'px';
      }
    }
    else
    {
        e.style.height="";
    }
    h=e.offsetHeight;
  }
  else if(css && xDef(e.style.pixelHeight)) {
    if(h>=0) e.style.pixelHeight=h;
    if(h==-1) e.style.pixelHeight="";
    h=e.style.pixelHeight;
  }
  return h;
}
