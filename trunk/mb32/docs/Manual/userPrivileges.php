<?php
/**
 * Copyright (c) 2011 Greg Riccardi, Fredrik Ronquist.
 * All rights reserved. This program and the accompanying materials
 * are made available under the terms of the GNU Public License v2.0
 * which accompanies this distribution, and is available at
 * http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * 
 * Contributors:
 *   Fredrik Ronquist - conceptual modeling and interaction design
 *   Austin Mast - conceptual modeling and interaction design
 *   Greg Riccardi - initial API and implementation
 *   Wilfredo Blanco - initial API and implementation
 *   Robert Bruhn - initial API and implementation
 *   Christopher Cprek - initial API and implementation
 *   David Gaitros - initial API and implementation
 *   Neelima Jammigumpula - initial API and implementation
 *   Karolina Maneva-Jakimoska - initial API and implementation
 *   Deborah Paul - initial API and implementation implementation
 *   Katja Seltmann - initial API and implementation
 *   Stephen Winner - initial API and implementation
 */
include_once('head.inc.php');
$title = 'About - Manual';
initHtml($title, NULL, NULL);
echoHead(false, $title);
?>


<div class="mainGenericContainer" width="100%">
    <!--change the header below -->
    <h1>Users and Their Privileges</h1>
    <div id=footerRibbon></div>
                            <!--<table class="manualContainer" cellspacing="0" width="100%">
                            <tr>-->
    <td width="100%">
        <br />
        <strong>User Login</strong>: Morphbank users who wish to access, add and modify data will
        be required to login into the system with a valid username and password
        issued by Morphbank. To obtain a username and password go to the <strong>Header Menu > Tools > Login</strong> and then click on
        <a href="<?php echo $config->domain; ?>About/Manual/loginUsername.php#newAccount">Request a Morphbank User Account</a> in the Login Box.
        Alternatively, contact the Morphbank admin. group at <strong><?php echo $config->email; ?></strong>.
        After login, members see a list of the groups to which they belong.
        Without login, only published (public) information that can be browsed or searched by the casual user (specimen,
        image, publication, locality, view, annotation, taxa, or collection data) can be seen.
        <br />

        <p>
            <em>In Morphbank v3, Morphbank groups can consist of members from diverse fields across kingdoms.</em>
        </p>

        <p><strong>Groups</strong>: Groups are comprised of users of the Morphbank system that
            share a common interest in a specific taxonomic area. <em>Members in
                groups have access to data owned by the group.</em> Prior to the data release
            date, only group members are allowed to view and comment on it. A
            Morphbank user may belong to more than one Morphbank group. Only Morphbank administrators
            and group coordinators have access to the group module that manages group
            membership.</p>

        <div class="specialtext3">
            Note: Group members may appoint a trusted person to submit data
            on their behalf. That person will obtain a Morphbank account under
            their own name and become a user. Users entering data are listed as
            the "<strong>Submitter</strong>" on various Morphbank records. If entering data on
            behalf of another Morphbank member, the "<strong>Submitter</strong>" will choose
            the Contributor's name from the appropriate drop-down list in the
            submission process.
        </div>
        <strong>Roles</strong>: users will be assigned one of many roles within groups. Users
        may have different roles in different groups but may only have one role
        in each group for which they are members. Roles dictate what a group member can do with
        regards to the objects contributed by the group. The <strong>Coordinator</strong> of the Group decides and can change
        the role of each member in the group.
        <div class="specialtext2">
            <ul>
                <li><strong>Guest</strong><em> without login</em> has read only access. No login (no Morphbank User Account) is required for this user role. A guest user is only allowed to view information that has passed the release date. The casual user cannot  make any data entries or annotations.
                </li>
                <li><strong>Guest</strong> <em>within a group</em>. This user has a Morphbank Account. Once logged in, the user
                    can see all the groups to which they belong. If they click on a group where they have a <strong>guest</strong> role, they
                    will be able to see objects contributed by that group (published and not). They will not have submit/edit/annotate privileges
                    for these objects.
                </li>
                <li><strong>Scientist</strong>: For objects the <strong>Scientist</strong> has contributed or submitted to a particular Group, they have the authorization to add/modify/delete those objects. They may as annotate released (public) images within their
                    taxon or images not released and owned by the group they belong to.
                </li>
                <li><strong>Lead Scientist</strong> has the same privileges as scientist but on all
                    objects owned by the group. A lead scientist can also be a coordinator or group manager and therefore manage users and
                    their permissions in a group. For now, a lead scientist sends a request to the Morphbank team
                    <strong><?php echo $config->email; ?></strong>
                    for creation of a group.
                </li>
                <li><strong>Coordinator</strong> has the same privileges as Lead Scientist and each
                    group may only have one Coordinator. In order to be assigned
                    a Group Coordinator, you must have lead scientist privileges for
                    that group or have been assigned by the Morphbank
                    administration. A coordinator can add and remove members
                    from the group, change a user's role, as well as request spin-off
                    groups to be developed with assigned coordinators. The
                    coordinator can appoint another lead scientist in the group as a
                    coordinator. Coordinators have access to the group module in order to add / remove members or change a group member's role.</li>
                <li><strong>Administrator</strong> There are very few individuals given Administrator
                    privileges. An Administrator has complete access to all data
                    and in addition can add/modify and/delete news, base or master
                    tables. Only someone with administrative privileges can add
                    new users to Morphbank and create new Morphbank groups for which there is no associated taxon. Those with administrative privileges have all rights in all groups and are responsible for managing the entire Morphbank
                    system.</li>
            </ul>
        </div>
        <strong>Submit</strong>: Access to input and modify data to the Morphbank database will
        be controlled by login access in accordance with the above security module.
        Users who have at least the role of scientists can add images, views,
        taxonomic names, localities, and specimens. Those with roles of at least
        a scientist can modify their own data while group coordinators and lead
        scientists may modify data owned by any member of their assigned group.
        <em>Users with this privilege must state (select) which group to which they are
            assigned before making such modifications.</em> Note: see reference to
        appointing a Submitter above.<br /><br />
        <strong>Browse</strong>: Everyone uses the My Manager tabbed-interface to browse the public data and images in Morphbank. All users may browse images and data that have been released,
        but only users with authorization through login will have the options to
        select Morphbank objects like images, specimens and views for edit/update, annotation and collection.
        <br /><br />

        <strong>Collections</strong>: Login and group selection is required for all users who wish
        to make, edit, or annotate a personal collection or view, edit or annotate
        collections from other members of the same group.

        <div class="specialtext3">
            <strong><a href="<?php echo $config->domain; ?>About/Manual/myManager.php" target="_blank">My Manager</a></strong> is Morphbank's user-interface. All users have access to this updated interface. Those with Morphbank accounts login to access additional features and functions. With My Manager, all objects in Morphbank are searchable via an <em>enhanced <strong>Keyword</strong>
                Search</em> and logged-in users can easily sort and <em>limit</em> searches to create, display, collect, edit, and annotate particular sets of objects.
        </div>
        <br />
        <br />
        <div id=footerRibbon></div>
        <table align="right">
            <td><a href="<?php echo $config->domain; ?>About/Manual/graphicGuide.php" class="button smallButton"><div>Next</div></a></td>
            <td><a href="<?php echo $config->domain; ?>About/Manual/index.php"class="button smallButton"><div>Contents</div></a></td>
        </table>
</div>

<?php
//Finish with end of HTML	
finishHtml();
?>	
