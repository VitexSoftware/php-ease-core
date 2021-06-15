#!groovy
pipeline {
    agent none

    options {
        ansiColor('xterm')
    }

    stages {
        stage('debian-stable') {
            agent {
                docker { image 'vitexsoftware/debian:stable' }
            }
            steps {
                dir('build/debian/package') {
                    checkout scm
		    buildPackage()
                }
                stash includes: 'dist/**', name: 'dist-debian'
            }

            post {
                success {
                    phpunit '**/target/php-ease-core/phpunit.xml'
                    archiveArtifacts '../*.deb'
                }
            }

        }

        stage('debian-testing') {
            agent {
                docker { image 'vitexsoftware/debian:testing' }
            }
            steps {
                dir('build/debian/package') {
                    sh 'if [ ! -d source ]; then git clone --depth 1 --single-branch $GIT_URL source ; else cd source; git pull; cd ..; fi;'
                    sh 'cd source ; debuild -i -us -uc -b ; cd ..'
                    sh 'mkdir -p $WORKSPACE/dist/debian/ ; mv *.deb *.changes *.build $WORKSPACE/dist/debian/'
                }
            }
        }
        stage('ubuntu-trusty') {
            agent {
                docker { image 'vitexsoftware/trusty:stable' }
            }
            steps {
                dir('build/debian/package') {
                    sh 'if [ ! -d source ]; then git clone --depth 1 --single-branch $GIT_URL source ; else cd source; git pull; cd ..; fi;'
                    sh 'cd source ; debuild -i -us -uc -b ; cd ..'
                    sh 'mkdir -p $WORKSPACE/dist/debian/ ; mv *.deb *.changes *.build $WORKSPACE/dist/debian/'
                }
            }
        }
        stage('ubuntu-hirsute') {
            agent {
                docker { image 'vitexsoftware/ubuntu:testing' }
            }
            steps {
                dir('build/debian/package') {
                    sh 'if [ ! -d source ]; then git clone --depth 1 --single-branch $GIT_URL source ; else cd source; git pull; cd ..; fi;'
                    sh 'cd source ; debuild -i -us -uc -b ; cd ..'
                    sh 'mkdir -p $WORKSPACE/dist/debian/ ; mv *.deb *.changes *.build $WORKSPACE/dist/debian/'
                }
            }
       }
    }
}

def buildPackage() {
    ansiColor('vga') {
      echo '\033[42m\033[97mBuild debian package for $(lsb_release -sd)\033[0m'
    }

    DISTRO = sh (
	script: 'lsb_release -sd',
        returnStdout: true
    ).trim()

	    debianPbuilder additionalBuildResults: '', 
	    components: '', 
	    distribution: $DISTRO, 
	    keyring: '', 
	    mirrorSite: 'http://deb.debian.org/debian/', 
	    pristineTarName: ''

    sh 'debuild -i -us -uc -b'
    sh 'ls -la ..'
    sh 'mkdir -p $WORKSPACE/dist/debian/ ; mv ../*.deb ../*.changes ../*.build $WORKSPACE/dist/debian/'
}
