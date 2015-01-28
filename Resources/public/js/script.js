/**
 * Prototype de la gestion des utilisateurs
 *
 * @author Olivier <sabinus52@gmail.com>
 *
 * @package Olix
 * @subpackage SecurityBundle
 */

var OlixSecurity = {

    /**
     * Selection d'un avatar par son image
     * 
     * @param object obj Lien de l'avatar
     */
    selectAvatarIMG: function(obj)
    {
        var avatar = $(obj).attr('href');
        this.selectAvatar(avatar, '/bundles/olixsecurity/avatar/');
        return false;
    },


    /**
     * Selection d'un avatar par son url
     * 
     * @param string url
     */
    selectAvatarURL: function(url)
    {
        this.selectAvatar(url);
        return false;
    },


    /**
     * Affecte l'avatar et ferme le popup
     */
    selectAvatar: function(avatar, prefix = '')
    {
        $('#imgAvatar').attr('src', prefix + avatar);
        $('#fos_user_profile_avatar').val(avatar);
        $('#modalAvatar').modal('hide');
    }

};