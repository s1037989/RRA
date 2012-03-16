package RRA::API::Stockbellringers;

use strict;
use warnings;

use base 'RRA::Base';
use SQL::Interp ':all';

sub cell_POST : Runmode {
	my $self = shift;
	my ($sql, @bind) = sql_interp 'UPDATE stockbellringers SET', {map {$_=>$self->param($_)} $self->param('celname')}, 'WHERE', {stockbellringer_id => $self->param('id')};
	return $self->to_json({sc=>'false',msg=>"Editing disabled"}) if $self->cfg('NOEDIT');
	$self->dbh->do($sql, {}, @bind) or return $self->to_json({sc=>'false',msg=>"Error: ".$self->dbh->errstr});
	return $self->to_json({sc=>'true',msg=>""});
}

sub edit_POST : Runmode {
	my $self = shift;
	my @stockbellringers = qw/stockbellringer/;
	my ($sql, @bind) = sql_interp 'UPDATE stockbellringers SET', {map {$_=>$self->param($_)} @stockbellringers}, 'WHERE', {stockbellringer_id => $self->param('id')};
	return $self->to_json({sc=>'false',msg=>"Editing disabled"}) if $self->cfg('NOEDIT');
	$self->dbh->do($sql, {}, @bind) or return $self->to_json({sc=>'false',msg=>"Error: ".$self->dbh->errstr});
	return $self->to_json({sc=>'true',msg=>""});
}

sub add_POST : Runmode {
	my $self = shift;
	my @stockbellringers = qw/stockbellringer/;
	my ($sql, @bind) = sql_interp 'INSERT INTO stockbellringers', {map {$_=>$self->param($_)} @stockbellringers};
	return $self->to_json({sc=>'false',msg=>"Editing disabled"}) if $self->cfg('NOADD');
	$self->dbh->do($sql, {}, @bind) or return $self->to_json({sc=>'false',msg=>"Error: ".$self->dbh->errstr});
	return $self->to_json({sc=>'true',msg=>""});
}

sub del_POST : Runmode {
	my $self = shift;
	my ($sql, @bind) = sql_interp 'DELETE FROM stockbellringers WHERE', {stockbellringer_id => $self->param('id')};
	return $self->to_json({sc=>'false',msg=>"Editing disabled"}) if $self->cfg('NODEL');
	$self->dbh->do($sql, {}, @bind) or return $self->to_json({sc=>'false',msg=>"Error: ".$self->dbh->errstr});
	return $self->to_json({sc=>'true',msg=>""});
}

sub search_POST : Runmode {
	my $self = shift;  
}
 
sub view_POST : Runmode {
	my $self = shift;
}

sub stockbellringers_POST : StartRunmode { #Authen Authz('admins') {
	my $self = shift;
	my %sOper = $self->sOper;
	my ($sidx, $sord, $page, $rows) = ($self->param('sidx')||'number', $self->param('sord')||'asc', $self->param('page')||1, $self->param('rows')||10);
	my ($sField, $sOper, $sValue) = ($self->param('searchField'), $self->param('searchOper'), $self->param('searchString'));
	my ($sql, @bind) = sql_interp 'SELECT count(*) FROM manage_stockbellringers_vw WHERE', ($sField&&$sOper{$sOper}? (\$sField,$sOper{$sOper},\$sValue) : ('1=1'));
	my $records = $self->dbh->selectrow_array($sql, {}, @bind);
	my $pages = $records > 0 ? int(($records / $rows) + 0.99) : 0;
	my $start = $page * $rows - $rows || 0;
	($sql, @bind) = sql_interp 'SELECT * FROM manage_stockbellringers_vw WHERE', ($sField&&$sOper{$sOper}? (\$sField,$sOper{$sOper},\$sValue) : ('1=1')), 'ORDER BY', \$sidx, \$sord, 'LIMIT', \$rows, 'OFFSET', \$start;
	return $self->to_json({page => $page, total => $pages, records => $records, rows => $self->dbh->selectall_arrayref($sql, {Slice=>{}}, @bind)});
}

1;
