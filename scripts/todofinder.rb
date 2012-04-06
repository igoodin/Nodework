#! /usr/bin/env ruby

require 'rubygems'
begin
	require 'term/ansicolor'
rescue
	abort("Install gem: term-ansicolor")
end
require 'find'
include Term::ANSIColor

#relative to '.'
searchDirs = [
	"../site/nodework",
	"../client",
	"../node",
	"."
]

def listFiles(dir)
	Find.find(dir) do |entry|
		if !File.directory? entry
			file = File.new(entry, "r")
			lineno = 1
			while (line = file.gets)
				if line =~ /^(\t*|\s*)?\/\/@todo/
					task = line.sub(/\/\/@todo/, '').strip!

					if ARGV.length > 0
						success = true
						ARGV.each do |arg|
							if task =~ /\(#{Regexp.quote(arg)}\)/
							else
								success = false
							end
						end

						if success
							print yellow("-#{task}"), "\n"
							puts "\t@#{entry}:#{lineno}\n\n"
						end
					else
						print yellow("-#{task}"), "\n"
						puts "\t@#{entry}:#{lineno}\n\n"
					end

				end
				lineno += 1
			end
			file.close
		end
	end
end

searchDirs.each { |dir| listFiles(dir) }
